<?php

namespace App\Repositories;

use App\Models\Bus;
use App\Models\Booking;
use App\Models\BusOperator;




// use App\Models\TicketPrice;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class DashboardRepository
{
    protected $seatOpen;
    // protected $extraseatOpen;

    
    public function __construct(Booking $booking,Bus $bus,BusOperator $busoperator)
    {
        $this->booking = $booking;       
            
        $this->bus = $bus; 
        $this->busoperator = $busoperator ;   
    }   

    public function getAll()
    {
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
        $data_arr['booking_profit'] = 486 ;
        $data_arr['cancellation_profit'] = 1641 ;
        $data_arr['sales_today'] = 2562 ;
        $data_arr['sales_this_week'] = 2562 ;
        $data_arr['sales_this_month'] = 2562 ;

        return $data_arr;     
    }
}