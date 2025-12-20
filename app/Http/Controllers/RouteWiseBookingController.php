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
    }


    public function allData(){
        $data = RouteWiseBooking::join('location as src', 'src.id', '=', 'route_wise_booking.source_id')
        ->join('location as dest', 'dest.id', '=', 'route_wise_booking.destination_id')
        ->select(
            'route_wise_booking.*',
            'src.name as source_name',
            'dest.name as destination_name'
        )
        ->get();

        return response()->json(["status"=> 200,"data"=>$data]);
    }
}
