<?php

namespace App\Repositories;

// use App\Models\Bus;
use App\Models\SeatBlock;
use App\Models\SeatBlockSeats;
use App\Models\BusSeats;
use App\Models\Bus;
use App\Models\Location;
use App\Models\TicketPrice;
use App\Models\Booking;
use App\Models\BusSeatCount;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


/*Priyadarshi to Review*/

class VendorSeatBlockRepository
{

    protected $seatBlock;
    protected $ticketPrice;
    protected $booking;
    protected $bookingDetail;
    protected $busSeats;
    protected $seatBlockSeats;
    protected $bus;
    protected $location;


    public function __construct(SeatBlock $seatBlock, SeatBlockSeats $seatsBlockSeats, BusSeats $busSeats, Bus $bus, Location $location, TicketPrice $ticketPrice, Booking $booking, BookingDetail $bookingDetail)
    {
        $this->seatBlock = $seatBlock;
        $this->seatBlockSeats = $seatsBlockSeats;
        $this->busSeats = $busSeats;
        $this->bus = $bus;
        $this->location = $location;
        $this->ticketPrice = $ticketPrice;
        $this->booking = $booking;
        $this->bookingDetail = $bookingDetail;
    }

    public function seatblockData($request)
    {
        $paginate   = $request['rows_number'] ?? 10;
        $name       = $request['name'] ?? null;
        $bus_id     = $request['bus_id'] ?? null;
        $page_no    = $request['page_no'] ?? 1;
        $fromDate   = $request['fromDate'] ?? null;
        $toDate     = $request['toDate'] ?? null;
        $busOperatorId = $request['bus_operator_id'] ?? null;
        $source_id  = $request['source_id'] ?? null;
        $destination_id = $request['destination_id'] ?? null;
        $userBusOperatorId = $request['USER_BUS_OPERATOR_ID'] ?? null;

        if ($paginate === 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        }

        $query = $this->busSeats
            ->select(
                'id',
                'bus_id',
                'ticket_price_id',
                'seats_id',
                'type',
                'operation_date',
                'reason',
                'other_reason',
                'status',
                'updated_at',
                'created_by',
                'vendor_id'
            )
            ->where('type', 2)
            ->whereNotnull('vendor_id')
            ->whereNotIn('status', [2])
            ->with([
                'bus:id,bus_operator_id,name,bus_number',
                'bus.busOperator:id,operator_name,organisation_name',
                'seats:id,seatText,berthType,bus_seat_layout_id',
                'ticketPrice:id,bus_id,source_id,destination_id'
            ]);

        /* ================= FILTERS ================= */

        if ($userBusOperatorId) {
            $query->whereHas(
                'bus',
                fn($q) =>
                $q->where('bus_operator_id', $userBusOperatorId)
            );
        }

        if ($busOperatorId) {
            $query->whereHas(
                'bus',
                fn($q) =>
                $q->where('bus_operator_id', $busOperatorId)
            );
        }

        if ($bus_id) {
            $query->where('bus_id', $bus_id);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('operation_date', [$fromDate, $toDate]);
        } else {
            $query->where('operation_date', now()->toDateString());
        }

        if ($name) {
            $query->where(function ($q) use ($name) {
                $q->whereHas(
                    'bus',
                    fn($b) =>
                    $b->where('name', 'like', "%{$name}%")
                )->orWhere('reason', 'like', "%{$name}%");
            });
        }

        if ($source_id && $destination_id) {
            $query->whereHas(
                'ticketPrice',
                fn($q) =>
                $q->where('source_id', $source_id)
                    ->where('destination_id', $destination_id)
            );
        }

        /* ================= FETCH ================= */

        $data = $query
            ->orderBy('operation_date', 'desc')
            ->get()
            ->groupBy(['bus_id', 'operation_date', 'ticket_price_id']);

        /* ================= SOURCE / DESTINATION ================= */

        $locationIds = [];

        foreach ($data as $busGroup) {
            foreach ($busGroup as $dateGroup) {
                foreach ($dateGroup as $routeGroup) {
                    $tp = $routeGroup->first()->ticketPrice;
                    if ($tp) {
                        $locationIds[] = $tp->source_id;
                        $locationIds[] = $tp->destination_id;
                    }
                }
            }
        }

        $locations = $this->location
            ->whereIn('id', array_unique($locationIds))
            ->pluck('name', 'id');

        foreach ($data as $busGroup) {
            foreach ($busGroup as $dateGroup) {
                foreach ($dateGroup as $routeGroup) {
                    foreach ($routeGroup as $seat) {
                        $tp = $seat->ticketPrice;
                        $seat->bus_source = $locations[$tp->source_id] ?? null;
                        $seat->bus_destination = $locations[$tp->destination_id] ?? null;
                    }
                }
            }
        }

        /* ================= PAGINATION ================= */

        return $this->customPaginate($data, $paginate, $page_no)
            ->withPath('/api/vendor-seatblockData');
    }

    public function addseatBlock($data)
    {
        DB::beginTransaction();

        try {
            $vendorId = implode(',', $data->vendor_id);

            // 1️⃣ Prepare dates
            $dates = collect($data->date ?? [])
                ->map(fn($d) => date('Y-m-d', strtotime($d)))
                ->toArray();

            // 2️⃣ Collect selected seats (upper + lower)
            $selectedSeats = [];

            foreach ($data['bus_seat_layout_data'] as $layout) {
                foreach (['upperBerth', 'lowerBerth'] as $berth) {
                    if (!empty($layout[$berth])) {
                        foreach ($layout[$berth] as $seat) {
                            if (($seat['seatChecked'] ?? false) === true) {
                                $selectedSeats[] = $seat;
                            }
                        }
                    }
                }
            }

            if (empty($selectedSeats)) {
                return ['status' => 'error', 'message' => 'No seats selected'];
            }

            // 3️⃣ Load ticket routes once
            $routes = $this->ticketPrice
                ->whereIn('id', $data['busRoute'])
                ->get()
                ->keyBy('id');

            // 4️⃣ Validation: blocked / booked check
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {

                        // Already blocked?
                        // $isBlocked = $this->busSeats
                        //     ->where([
                        //         'bus_id' => $data['bus_id'],
                        //         'seats_id' => $seat['seatId'],
                        //         'ticket_price_id' => $ticketPriceId,
                        //         'operation_date' => $dt,
                        //         'type' => $data['type'],
                        //         'status' => 1,
                        //         'vendor_id' =>$venderId
                        //     ])->exists();

                        // if ($isBlocked) {
                        //     return [
                        //         'status' => 'error',
                        //         'message' => "Seat no {$seat['seatText']} is already blocked for date - {$dt}"
                        //     ];
                        // }

                        // Already booked?
                        $route = $routes[$ticketPriceId];

                        $isBooked = $this->bookingDetail
                            ->whereHas('booking', function ($q) use ($data, $dt, $route) {
                                $q->where([
                                    'bus_id' => $data['bus_id'],
                                    'journey_dt' => $dt,
                                    'source_id' => $route->source_id,
                                    'destination_id' => $route->destination_id,
                                ])->whereIn('status', [1, 4]);
                            })
                            ->whereHas('BusSeats', function ($q) use ($seat) {
                                $q->where('seats_id', $seat['seatId']);
                            })
                            ->exists();

                        if ($isBooked) {
                            return [
                                'status' => 'error',
                                'message' => "Seat no {$seat['seatText']} is already booked"
                            ];
                        }
                    }
                }
            }

            // 5️⃣ Insert blocked seats
            foreach ($selectedSeats as $seat) {
                foreach ($data['busRoute'] as $ticketPriceId) {
                    foreach ($dates as $dt) {
                        $ins = [
                            'vendor_id' => $vendorId,
                            'bus_id' => $data['bus_id'],
                            'category' => 0,
                            'seats_id' => $seat['seatId'],
                            'ticket_price_id' => $ticketPriceId,
                            'operation_date' => $dt,
                            'status' => 1,
                            'type' => $data['type'],
                            'created_by' => $data['created_by'],
                            'reason' => $data['reason'],
                            'other_reason' => $data['other_reson'],
                        ];
                        $this->busSeats->create($ins);
                        // return $ins;
                    }
                }
            }

            

            // $inventory = app(\App\Services\InventoryService::class);

            // $seatCount = count($selectedSeats);

            // foreach ($data['busRoute'] as $ticketPriceId) {

            //     foreach ($dates as $dt) {

            //         $inventory->blockSeatsByTicketPrice(
            //             $ticketPriceId,
            //             $dt,
            //             $seatCount
            //         );
            //     }
            // }


            DB::commit();
            return ['status' => 'success'];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("rollback");
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }


    public function updateSeatBlockData($data)
    {
        DB::beginTransaction();

        try {

            $requestedSeats = [];

            foreach ($data['bus_seat_layout_data'] as $layout) {

                foreach (['upperBerth', 'lowerBerth'] as $berth) {

                    if (!empty($layout[$berth])) {

                        foreach ($layout[$berth] as $seat) {

                            // $requestedSeats[$seat['seatId']] =
                            //     filter_var(
                            //         $seat['seatChecked'] ?? false,
                            //         FILTER_VALIDATE_BOOLEAN
                            //     );

                            $requestedSeats[$seat['seatId']] = [
                                'checked'  => filter_var($seat['seatChecked'] ?? false, FILTER_VALIDATE_BOOLEAN),
                                'seatText' => $seat['seatText'],
                            ];
                        }
                    }
                }
            }

            $busRoute = $this->busSeats
                ->where('bus_id', $data['bus_id'])
                ->where('operation_date', $data['date'])
                ->where('status', 1)
                ->pluck('ticket_price_id')
                ->unique()
                ->values()
                ->toArray();

            foreach ($busRoute as $ticketPriceId) {

                $oldBlockedSeatCount = $this->busSeats
                    ->where('bus_id', $data['bus_id'])
                    ->where('ticket_price_id', $ticketPriceId)
                    ->where('operation_date', $data['date'])
                    ->where('type', 2)
                    ->where('status', 1)
                    ->count();


                $route = $this->ticketPrice->find($ticketPriceId);

                // booked seats cannot be blocked
                $bookedSeatIds = $this->bookingDetail
                    ->whereHas('booking', function ($q) use ($data, $route) {

                        $q->where('bus_id', $data['bus_id'])
                            ->where('journey_dt', $data['date'])
                            ->where('source_id', $route->source_id)
                            ->where('destination_id', $route->destination_id)
                            ->whereIn('status', [1, 4]);
                    })
                    ->pluck('bus_seats_id')
                    ->toArray();

                $existingSeats = $this->busSeats
                    ->where('bus_id', $data['bus_id'])
                    ->where('ticket_price_id', $ticketPriceId)
                    ->where('operation_date', $data['date'])
                    ->where('type', 2) // block seat
                    ->get()
                    ->groupBy('seats_id');

                foreach ($requestedSeats as $seatId => $seatData) {

                    $isChecked = $seatData['checked'];
                    $seatText  = $seatData['seatText'];

                    // skip booked seats
                    // if (in_array($seatId, $bookedSeatIds)) {
                    //     continue;
                    // }

                    $isBooked = $this->bookingDetail
                        ->whereHas('booking', function ($q) use ($data, $route) {

                            $q->where('bus_id', $data['bus_id'])
                                ->where('journey_dt', $data['date'])
                                ->where('source_id', $route->source_id)
                                ->where('destination_id', $route->destination_id)
                                ->whereIn('status', [1, 4]);
                        })
                        ->whereHas('BusSeats', function ($q) use ($seatId) {
                            $q->where('seats_id', $seatId);
                        })
                        ->exists();

                    if ($isBooked && $isChecked) {
                        return [
                            'status' => 'error',
                            'message' => "Seat No {$seatText} is already booked and cannot be blocked."
                        ];
                    }

                    $seatRows = $existingSeats->get($seatId, collect());

                    /*
                    |--------------------------------------------------------------------------
                    | BLOCK SEAT
                    |--------------------------------------------------------------------------
                    */
                    if ($isChecked) {

                        if ($seatRows->count() > 0) {

                            $first = $seatRows->first();

                            $first->update([
                                'status' => 1,
                                'reason' => $data['reason'],
                                'other_reason' => $data['other_reson']
                            ]);

                            // remove duplicates
                            if ($seatRows->count() > 1) {

                                $duplicateIds = $seatRows
                                    ->pluck('id')
                                    ->slice(1)
                                    ->values()
                                    ->toArray();

                                $this->busSeats
                                    ->whereIn('id', $duplicateIds)
                                    ->delete();
                            }
                        } else {

                            $this->busSeats->create([
                                'bus_id'          => $data['bus_id'],
                                'category'        => 0,
                                'seats_id'        => $seatId,
                                'ticket_price_id' => $ticketPriceId,
                                'operation_date'  => $data['date'],
                                'status'          => 1,
                                'type'            => 2,
                                'created_by'      => $data['created_by'],
                                'reason'          => $data['reason'],
                                'other_reason'    => $data['other_reson'],
                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UNBLOCK SEAT
                    |--------------------------------------------------------------------------
                    */ else {

                        if ($seatRows->count()) {

                            $this->busSeats
                                ->whereIn(
                                    'id',
                                    $seatRows->pluck('id')->toArray()
                                )
                                ->update([
                                    'status' => 2
                                ]);
                        }
                    }
                }

                $newBlockedSeatCount = $this->busSeats
                    ->where('bus_id', $data['bus_id'])
                    ->where('ticket_price_id', $ticketPriceId)
                    ->where('operation_date', $data['date'])
                    ->where('type', 2)
                    ->where('status', 1)
                    ->count();

                BusSeatCount::where('ticket_price_id', $ticketPriceId)
                    ->where('journey_date', $data['date'])
                    ->update([
                        'blocked_seat' => $newBlockedSeatCount
                    ]);

                $inventory = app(\App\Services\InventoryService::class);

                $inventory->refreshAvailableSeats(
                    [$ticketPriceId],
                    $data['date']
                );
            }

            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Seat block data synced successfully'
            ];
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error(
                'updateSeatBlockData Error : ' .
                    $e->getMessage()
            );

            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateseatBlock($data, $id)
    {
        $setblock = $this->seatBlock->find($id);
        $setblock->seatBlockSeats()->where('seat_block_id', $id)->delete();
        $setblock->delete();

        $seatBlock = new $this->seatBlock;
        $seatBlock->bus_id = $data['bus_id'];
        $seatBlock->operator_id = $data['bus_operator_id'];
        $seatBlock->reason = $data['reason'];
        $seatBlock->date_applied = $data['date'];
        $seatBlock->created_by = $data['created_by'];
        $seatBlock->save();
        $seats = [];
        foreach ($data['bus_seat_layout_data'] as $slayout) {
            foreach ($slayout['lowerBerth'] as $lberth) {
                $seat = new seatBlockSeats();
                if (isset($lberth['seatChecked'])) {
                    if ($lberth["seatChecked"] == 'true') {
                        $seat['seats_id'] = $lberth['seatId'];
                        $seat['created_by'] = $data['created_by'];
                        $seats[] = $seat;
                    }
                }
            }

            foreach ($slayout['upperBerth'] as $uberth) {
                $seat = new seatBlockSeats();
                if (isset($uberth['seatChecked'])) {
                    if ($uberth["seatChecked"] == 'true') {
                        $seat['seats_id'] = $uberth['seatId'];
                        $seat['created_by'] = $data['created_by'];
                        $seats[] = $seat;
                    }
                }
            }
        }
        $seatBlock->seatBlockSeats()->saveMany($seats);
        return $seatBlock;
    }

    public function delete($request)
    {
        $inventory = app(\App\Services\InventoryService::class);

        $seatBlock = $this->busSeats
            ->where('bus_id', $request['bus_id'])
            ->where('operation_date', $request['operationDate'])
            ->where('type', $request['type'])
            ->whereNotnull('vendor_id')
            ->delete();

        $routeIds = TicketPrice::where('bus_id', $request['bus_id'])
            ->pluck('id')
            ->toArray();

        foreach ($routeIds as $ticketPriceId) {

            $normalBlocked = $this->busSeats
                ->where('bus_id', $request['bus_id'])
                ->where('ticket_price_id', $ticketPriceId)
                ->where('operation_date', $request['operationDate'])
                ->where('type', 2)
                ->where('status', 1)
                ->count();

            $extraBlocked = $this->busSeats
                ->where('bus_id', $request['bus_id'])
                ->where('ticket_price_id', $ticketPriceId)
                ->where('operation_date', $request['operationDate'])
                ->whereNull('type')
                ->whereNotNull('duration')
                ->where('status', 1)
                ->count();

            // BusSeatCount::where('ticket_price_id', $ticketPriceId)
            //     ->where('journey_date', $request['operationDate'])
            //     ->update([
            //         'blocked_seat' => ($normalBlocked + $extraBlocked)
            //     ]);

            // $inventory->refreshAvailableSeats(
            //     [$ticketPriceId],
            //     $request['operationDate']
            // );
        }

        return $seatBlock;
    }

    public function editseatblock($request)
    {
        // log::info($request);

        $seatBlock = $this->busSeats->with('bus', 'seats')
            ->where('bus_id', $request['bus_id'])
            ->where('operation_date', $request['operation_date'])
            ->where('type', $request['type'])
            ->where('ticket_price_id', $request['ticket_price_id'])
            ->where('status', 1)
            ->get();

        // log::info($seatBlock);

        return $seatBlock;
    }


    public function customPaginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    public function changeStatus($id)
    {
        $post = $this->seatBlock->find($id);
        if ($post->status == 0) {
            $post->status = 1;
        } elseif ($post->status == 1) {
            $post->status = 0;
        }
        $post->update();
        return $post;
    }



    public function alreadyBlocks($request)
    {
        $check_dt = date('Y-m-d', strtotime('today - 1 days'));

        $ticketPrice = $this->ticketPrice->where('bus_id', $request->bus_id)->get();
        $data = $this->busSeats->with('seats')
            ->where('bus_id', $request->bus_id)
            ->where('operation_date', '>', $check_dt)
            ->where('ticket_price_id', $ticketPrice[0]->id)
            ->where('type', 2)
            ->whereNotnull('vendor_id')
            ->where('status', 1)->get()->groupBy(['operation_date']);
        return $data;
    }
}
