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

    public function getAll($request)
    {
        $dt_month = date('Y-m-d', strtotime('today - 30 days'));
        $dt_week = date('Y-m-d', strtotime('today - 7 days'));
        $current_month=date('Y-m');
        $data_arr = array();
        $current_date =date('Y-m-d');
        $today_data = $this->booking->where('status','1');
        $upcoming_data = $this->booking->where('status','1');
        $bus_data = $this->bus->where('status','1');
        $operator_data = $this->busoperator->where('status','1');
        $booking_data=$this->booking->selectRaw('sum(odbus_charges) as odbus_amount')->where('status','1');
        $sales_data=$this->booking->selectRaw('sum(owner_fare) as today_amount')->where('status','1');

        switch ($request['rangeFor']) {
            case 'This Month':
                $today_data = $today_data->where('created_at','Like',$current_month.'%')->get();
                $upcoming_data = $upcoming_data->where('journey_dt','Like',$current_month.'%')->get();
                $bus_data = $bus_data->where('created_at','Like',$current_month.'%')->get();
                $operator_data = $operator_data->where('created_at','Like',$current_month.'%')->get();
                $booking_data = $booking_data->where('created_at','Like',$current_month.'%');
                $sales_data = $sales_data->where('created_at','Like',$current_month.'%');
                break;
            
            case 'This Week':
                $today_data = $today_data->whereBetween('created_at',[$dt_week,$current_date])->get();
                $upcoming_data = $upcoming_data->where('journey_dt',[$dt_week,$current_date])->get();
                $bus_data = $bus_data->where('created_at',[$dt_week,$current_date])->get();
                $operator_data = $operator_data->where('created_at',[$dt_week,$current_date])->get();
                $booking_data = $booking_data->where('created_at',[$dt_week,$current_date]);
                $sales_data = $sales_data->where('created_at',[$dt_week,$current_date]);
                break;
            
            case 'Today':
                $today_data = $today_data->where('created_at','Like',$current_date.'%')->get();
                $upcoming_data = $upcoming_data->where('journey_dt','Like',$current_date.'%')->get();
                $bus_data = $bus_data->where('created_at','Like',$current_date.'%')->get();
                $operator_data = $operator_data->where('created_at','Like',$current_date.'%')->get();
                $booking_data = $booking_data->where('created_at','Like',$current_date.'%');
                $sales_data = $sales_data->where('created_at','Like',$current_date.'%');
                break;
            
            case 'Custom Range':
                $today_data = $today_data->whereBetween('created_at',[$request['rangeFrom'],$request['rangeTo']])->get();
                $upcoming_data = $upcoming_data->whereBetween('journey_dt',[$request['rangeFrom'],$request['rangeTo']])->get();
                $bus_data = $bus_data->where('created_at',[$request['rangeFrom'],$request['rangeTo']])->get();
                $operator_data = $operator_data->where('created_at',[$request['rangeFrom'],$request['rangeTo']])->get();
                $booking_data = $booking_data->where('created_at',[$request['rangeFrom'],$request['rangeTo']]);
                $sales_data = $sales_data->where('created_at',[$request['rangeFrom'],$request['rangeTo']]);
                break;    
            
            default:
                $today_data = $today_data->get();
                $upcoming_data = $upcoming_data->get();
                $bus_data = $bus_data->get();
                $operator_data = $operator_data->get();
                $booking_data = $booking_data;
                $sales_data = $sales_data;
                break;
        }
        $today_data =count($today_data);        
        $upcoming_data = count($upcoming_data);        
        $active_bus_data = count($bus_data);
        $active_operator_data = count($operator_data);
        $data_arr['today_pnr'] = $today_data;
        $data_arr['upcoming_pnr'] = $upcoming_data;
        $data_arr['active_bus'] = $active_bus_data;
        $data_arr['active_operator'] = $active_operator_data;
        $data_arr['booking_profit'] = $booking_data->get();
        $data_arr['cancellation_profit'] = 1641 ; //MADE STATIC
        $data_arr['sales_data']=$sales_data->get();
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
                          ->where('status','1')
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
    
    public function getOperatorName($operatorId)
    { 
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
        ->where('status','1')
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

    public function getticketstatics()
    {        
       return "WORK IN PROGRESS";
    }


    public function getbookingbydevice()
    {        
       return "WORK IN PROGRESS";
    }


    public function getpnrstatics()
    {        
        $pnr_data = $this->booking
                          ->select('journey_dt')
                          ->selectRaw('count(*) as pnr_count')
                          ->groupBy('journey_dt')
                          ->orderBy('journey_dt','DESC')
                          ->where('status','1')
                          ->limit('7')
                          ->get();
        $data_arr = array();

        $date_arr = array();
        $pnr_count = array();

        foreach($pnr_data as $v)
        {
            $date_arr[] = $v->journey_dt;
            $pnr_count[] = $v->pnr_count;
        } 
        $data_arr['date'] = $date_arr ; 
        $data_arr['pnr'] = $pnr_count ;

        return $data_arr;
    }
}

