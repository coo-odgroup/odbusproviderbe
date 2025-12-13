<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\RouteWiseBooking;
use App\Models\TicketPrice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RouteWiseBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0); // unlimited
        ini_set('max_execution_time', 0);
        
        $data = RouteWiseBooking::find($this->request);
        // Log::info($this->request);
        // return;
        $startDate = $data['fromdate'];
        // Log::info($startDate);
        $endDate   = $data["todate"];
        // return [$startDate,$endDate];exit;
        $busIds = TicketPrice::where('source_id', $data["source_id"])
            ->where('destination_id', $data["destination_id"])
            ->where('status', 1)
            ->limit(10)
            ->pluck('bus_id');

        // return $busIds;
        $ticketPrices = TicketPrice::whereIn('bus_id', $busIds)
            ->with('operator:id,operator_name')
            ->with('busdata:id,name,bus_number')
            ->with('source:id,name')
            ->with('destination:id,name')
            ->get();

        $result = [];

        foreach ($ticketPrices as $t) {

            $bookings = Booking::where('bus_id', $t->bus_id)
                ->where('source_id', $t->source_id)
                ->where('destination_id', $t->destination_id)
                ->with('usersData:id,name,email,phone')
                ->with('booking_details.seat:id,berthType,seatText')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();



            // if no booking → skip
            if ($bookings->isEmpty()) {
                continue;
            }

            $t->bookings = $bookings;

            $result[] = $t;
            sleep(2);
        }
        // return $result;

        $path = uniqid()."routewise-bookings.csv";

        $csvPath = storage_path('app/'.$path);
        $fileExists = file_exists($csvPath);

        $file = fopen($csvPath, 'a');

        // Header
        if (!$fileExists) {
            fputcsv($file, [
                'Pnr No',
                'Seat No',
                'Sleeper No',
                'Bus Number',
                'Bus Name',
                'Source- Destination',
                'Booking Date',
                'Journey Date',
                'Passenger Details',
                'User Name',
                'User Phone',
                'User Email'
            ]);
        }

        foreach ($result as $item) {
            foreach ($item->bookings as $b) {

                // SEAT & SLEEPER
                $seatNo = '';
                $sleeperNo = '';

                if (!empty($b->booking_details)) {
                    foreach ($b->booking_details as $bd) {

                        // If seat object exists and has berthType
                        if (!empty($bd->seat) && !empty($bd->seat->berthType)) {

                            if ($bd->seat->berthType == 'Seat') {
                                $seatNo .= ($bd->seat->seatText ?? $bd->seat_name) . ',';
                            } else {
                                $sleeperNo .= ($bd->seat->seatText ?? $bd->seat_name) . ',';
                            }
                        } else {
                            // If seat relation null → use seat_name fallback
                            $seatNo .= ($bd->seat_name ?? '') . ',';
                        }
                    }
                }

                $seatNo = rtrim($seatNo, ',');
                $sleeperNo = rtrim($sleeperNo, ',');

                // PASSENGER DETAILS (Name, Gender, Age)
                $passengerDetails = '';
                if (!empty($b->booking_details)) {
                    foreach ($b->booking_details as $bd) {
                        $passengerDetails .= $bd->passenger_name . " (" . $bd->passenger_gender . ", " . $bd->passenger_age . "), ";
                    }
                }
                $passengerDetails = rtrim($passengerDetails, ', ');

                //source name
                $source_name = $item->source->name ?? '';
                $destination_name = $item->destination->name ?? '';

                // USER DETAILS (users_data)
                $userName  = $b->usersData->name  ?? '';
                $userPhone = $b->usersData->phone ?? '';
                $userEmail = $b->usersData->email ?? '';

                // WRITE CSV ROW
                fputcsv($file, [
                    $b->pnr,
                    $seatNo,
                    $sleeperNo,
                    $item->busdata->bus_number,
                    $item->busdata->name,
                    $source_name . " - " . $destination_name,
                    $b->created_at,
                    $b->journey_dt,
                    $passengerDetails,
                    $userName,
                    $userPhone,
                    $userEmail,
                ]);
            }
        }

        fclose($file);

        $statusData =[
            "running_status" => 1,
            "download_file" => 'app/'.$path
        ];

        RouteWiseBooking::where('id',$this->request)->update($statusData);

        return response()->json([
            'message' => 'CSV created successfully',
            'path' => "app/routewise-bookings.csv"
        ]);

    }
}
