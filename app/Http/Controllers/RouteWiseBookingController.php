<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Location;
use App\Models\RouteWiseBooking;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use App\Jobs\RouteWiseBookingJob;
use Carbon\Carbon;

class RouteWiseBookingController extends Controller
{
    public function routewiseBooking(Request $request)
    {
        $data = [
            "source_id" => $request->source_id,
            "destination_id" => $request->destination_id,
            "fromdate" => $request->fromdate,
            "todate" => $request->todate,
            "running_status" => 0,
        ];


        RouteWiseBooking::create($data);
        
        $delay = 0;

        $pendingReports = RoutewiseBooking::where('running_status',0)->get();

        foreach($pendingReports as $report){
            RouteWiseBookingJob::dispatch($report->id)->delay(Carbon::now()->addMinutes($delay));
            $delay ++;
        }


        return response()->json(["status"=>200,"msg"=>"Job Created successfully..."]);


        // return $data;
        // RouteWiseBooking::create($data);
        // $data = RouteWiseBooking::findOrFail($this->request);

        // $startDate = $request->fromdate;
        // $endDate   = $request->todate;

        // // Get bus IDs
        // $busIds = TicketPrice::where('source_id', $request->source_id)
        //     ->where('destination_id', $request->destination_id)
        //     ->where('status', 1)
        //     ->pluck('bus_id')
        //     ->unique();

        // // Get ticket prices
        // $ticketPrices = TicketPrice::whereIn('bus_id', $busIds)
        //     ->with([
        //         'operator:id,operator_name',
        //         'busdata:id,name,bus_number',
        //         'source:id,name',
        //         'destination:id,name',
        //     ])
        //     ->get();

        // // CSV file
        // $path = uniqid() . '_routewise-bookings.csv';
        // $csvPath = storage_path('app/' . $path);
        // $file = fopen($csvPath, 'w');

        // // CSV header
        // fputcsv($file, [
        //     'Pnr No',
        //     'Seat No',
        //     'Sleeper No',
        //     'Bus Number',
        //     'Bus Name',
        //     'Source - Destination',
        //     'Booking Date',
        //     'Journey Date',
        //     'Passenger Details',
        //     'User Name',
        //     'User Phone',
        //     'User Email'
        // ]);

        // foreach ($ticketPrices as $t) {

        //     $bookings = Booking::where('bus_id', $t->bus_id)
        //         ->where('source_id', $t->source_id)
        //         ->where('destination_id', $t->destination_id)
        //         ->whereBetween('created_at', [$startDate, $endDate])
        //         ->with([
        //             'usersData:id,name,email,phone',
        //             'booking_details:id,booking_id,bus_seats_id,seat_name,passenger_name,passenger_gender,passenger_age',
        //             'booking_details.seat', // ✅ hasOneThrough
        //         ])
        //         ->get();

        //     if ($bookings->isEmpty()) {
        //         continue;
        //     }

        //     foreach ($bookings as $b) {

        //         $seatNo = '';
        //         $sleeperNo = '';
        //         $passengerDetails = '';

        //         foreach ($b->booking_details as $bd) {

        //             // Passenger details
        //             $passengerDetails .= $bd->passenger_name . ' (' .
        //                 $bd->passenger_gender . ', ' .
        //                 $bd->passenger_age . '), ';

        //             // ✅ CORRECT SEAT / SLEEPER LOGIC
        //             if ($bd->seat) {

        //                 $berthType = (int) $bd->seat->berthType;

        //                 if ($berthType === 1) {
        //                     // Seat / Lower
        //                     $seatNo .= ($bd->seat->seatText ?? $bd->seat_name) . ',';
        //                 } elseif ($berthType === 2) {
        //                     // Sleeper / Upper
        //                     $sleeperNo .= ($bd->seat->seatText ?? $bd->seat_name) . ',';
        //                 }
        //             } elseif (!empty($bd->seat_name)) {
        //                 // fallback for old data
        //                 if (preg_match('/sl|sleep|berth/i', $bd->seat_name)) {
        //                     $sleeperNo .= $bd->seat_name . ',';
        //                 } else {
        //                     $seatNo .= $bd->seat_name . ',';
        //                 }
        //             }
        //         }

        //         fputcsv($file, [
        //             $b->pnr,
        //             rtrim($seatNo, ','),
        //             rtrim($sleeperNo, ','),
        //             $t->busdata->bus_number ?? '',
        //             $t->busdata->name ?? '',
        //             ($t->source->name ?? '') . ' - ' . ($t->destination->name ?? ''),
        //             $b->created_at,
        //             $b->journey_dt,
        //             rtrim($passengerDetails, ', '),
        //             $b->usersData->name ?? '',
        //             $b->usersData->phone ?? '',
        //             $b->usersData->email ?? '',
        //         ]);
        //     }
        // }

        // fclose($file);

        // Update job status
        // RouteWiseBooking::where('id', $this->request)->update([
        //     'running_status' => 1,
        //     'download_file'  => 'app/' . $path,
        // ]);

        // return response()->json([
        //     'message' => 'CSV created successfully',
        //     'path'    => 'app/' . $path,
        // ]);

        // $delay = 0;

        // $pendingReports = RoutewiseBooking::where('running_status',0)->get();

        // foreach($pendingReports as $report){
        //     RouteWiseBookingJob::dispatch($report->id)->delay(Carbon::now()->addMinutes($delay));
        //     $delay ++;
        // }


        return response()->json(["status" => 200, "msg" => "Job Created successfully..."]);
    }


    public function allData()
    {
        $data = RouteWiseBooking::join('location as src', 'src.id', '=', 'route_wise_booking.source_id')
            ->join('location as dest', 'dest.id', '=', 'route_wise_booking.destination_id')
            ->select(
                'route_wise_booking.*',
                'src.name as source_name',
                'dest.name as destination_name'
            )
            ->get();

        return response()->json(["status" => 200, "data" => $data]);
    }
}
