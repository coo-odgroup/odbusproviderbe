<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use App\Models\BusOperator;
use DB;


// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class DashboardRepository
{
    protected $seatOpen;
    protected $booking;
    protected $location;
    protected $busoperator;

    
    public function __construct(Booking $booking,Bus $bus,BusOperator $busoperator,Location $location)
    {
        $this->booking = $booking;           
        $this->bus = $bus; 
        $this->location = $location;       
        $this->busoperator = $busoperator ;   
    }   

    public function getAll()
    {
        $dt_month = date('Y-m-d', strtotime('today - 30 days'));
        $dt_week = date('Y-m-d', strtotime('today - 7 days'));

        
        $data_arr = array();
        
        $current_date =date('Y-m-d');
        $today_data = $this->booking->where('status','1')->where('created_at','Like',$current_date.'%')->get() ;         
        $today_data =count($today_data);

        $upcoming_data = $this->booking->where('status','1')->where('journey_dt','>',$current_date)->get() ;
        $upcoming_data = count($upcoming_data);

        $bus_data = $this->bus->where('status','1')->get() ;
        $active_bus_data = count($bus_data);

        $operator_data = $this->busoperator->where('status','1')->get() ;
        $active_operator_data = count($operator_data);

        
        
        $data_arr['today_pnr'] = $today_data;
        $data_arr['upcoming_pnr'] = $upcoming_data;
        $data_arr['active_bus'] = $active_bus_data;
        $data_arr['active_operator'] = $active_operator_data;

        $data_arr['booking_profit'] = $this->booking                         
                                        ->selectRaw('sum(odbus_charges) as odbus_amount')
                                        ->where('created_at','>',$dt_month)
                                        ->where('status','1')
                                        ->get();  ;
        $data_arr['cancellation_profit'] = 1641 ;

        $data_arr['sales_today'] = $this->booking                         
                                        ->selectRaw('sum(owner_fare) as today_amount')
                                        ->where('journey_dt',$current_date)
                                        ->where('status','1')
                                        ->get(); 
        $data_arr['sales_this_week'] = $this->booking                         
                                        ->selectRaw('sum(owner_fare) as weak_amount')
                                        ->where('journey_dt','>',$dt_week)
                                        ->where('status','1')
                                        ->get();
        $data_arr['sales_this_month'] = $this->booking                         
                                        ->selectRaw('sum(owner_fare) as month_amount')
                                        ->where('journey_dt','>',$dt_month)
                                        ->where('status','1')
                                        ->get();

        return $data_arr;     
    }

    public function getRoute()
    {
        $dt = date('Y-m-d', strtotime('today - 30 days'));

       $route_data = $this->booking
                          ->select(['source_id', 'destination_id'])
                          ->selectRaw('count(*) as pnr_count')
                          ->selectRaw('sum(owner_fare) as amount')
                          ->groupBy(['source_id', 'destination_id'])
                          ->orderBy('pnr_count','DESC')
                          ->where('journey_dt','>',$dt)
                          ->limit(5)
                          ->get();

        $data_arr = array();
        foreach($route_data as $key=>$v)
        {
            $data_arr[]=$v->toArray();
            $data_arr[$key]['from_location']=$this->location->where('id', $v->source_id)->get();
            $data_arr[$key]['to_location']=$this->location->where('id', $v->destination_id)->get();
        } 
       
        return $data_arr;  


    }
    
    public function getOperatorName($operatorId){ 
        $records = $this->bus
        ->with('busOperator')
        ->where('id',$operatorId)->get();
        $operatorName = $records[0]->busOperator->operator_name;
        return $operatorName;
    }

    public function getOperator()
    {
        $dt = date('Y-m-d', strtotime('today - 30 days'));
        // $operator_data = $this->booking->where('journey_dt','>',$dt)->get();

        $busIds = $this->booking
        ->select('bus_id',(DB::raw('count(*) as count')))
        ->selectRaw('sum(owner_fare) as amount')
        ->whereDate('created_at', '>', $dt)
        ->groupBy('bus_id')
        ->orderBy('count', 'DESC')
        ->get();
            if($busIds->isEmpty()){
                return "No booking exist to filter top operator";
            }
            else{
                foreach($busIds as $busId){
                    $opId = $busId->bus_id;
                    $count = $busId->count;
                    $opName = $this->getOperatorName($opId);
                    $topOperators[] = array(
                        "operatorName" => $opName, 
                        "count" => $count,
                        "amount" => $busId->amount
                        );
                } 
            }
            return $topOperators;
    }
}

