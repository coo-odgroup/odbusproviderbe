<?php
namespace App\Repositories;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\Location;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

use App\Jobs\SendCancelTicketEmailJob;

class TicketInformationRepository
{
 
    protected $location;
    protected $booking;
    protected $bus;

    public function __construct(Location $location, Bus $bus,Booking $booking)
    {
        $this->location = $location;
        $this->bus = $bus;
        $this->booking = $booking;
    }
    public function getpnrdetails($request)
    {
        $date = date('Y-m-d',strtotime("-1 days"));
        // log::info($date);
        // exit;
        $pnr_Details = $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice',
                                    'Bus','Users','CustomerPayment')
                             ->with('bus.busstoppage')
                             ->where('pnr',$request[0])
                             ->where('status',1)
                             ->where('journey_dt','>',$date)
                             ->whereHas('CustomerPayment', function ($query) {$query->where('payment_done', '1' );})
                             ->orderBy('id','DESC')->get();
        // log::info($pnr_Details);
          if($pnr_Details){
            foreach($pnr_Details as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               //   $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
 
               // foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
               //  {                          
               //      $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
               //      $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
               //  }
               //  $v['source']= $stoppages['source'];
               //  $v['destination']= $stoppages['destination'];
               }
           }
        return $pnr_Details;
    }

    public function cancelticket($request)
    {
        // Log::info($request['pnr']);
        // exit;
        $id=$request->id ;
        $cancelticket = $this->booking->find($id);       
        $cancelticket->deduction_percent = $request['percentage_deduct'];
        $cancelticket->refund_amount = $request['refund_amount'];
        $cancelticket->cancel_reason = $request['reason'];             
        $cancelticket->cancel_by = $request['cancelled_by'];
        $cancelticket->status = $request['status'];

        $cancelticket->update();
        
           $to_user = 'bishal.seofied@gmail.com';
         
           $subject = "Ticket Cancel( Pnr.no-".$request->pnr." )";
           $data= ['pnr'=>$request['pnr'],
                    'refund_amount' => $request['refund_amount'],
                    'deduction_percent'=> $request['percentage_deduct']
                  ] ;
           SendCancelTicketEmailJob::dispatch($to_user, $subject, $data);

        return $cancelticket;
       
    }

    public function cancelticketdata($request)
    {
       
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->booking->with('BookingDetail.BusSeats.seats',
                                    'BookingDetail.BusSeats.ticketPrice','Bus','Users',
                                    'CustomerPayment')
                             ->where('status',2)
                             ->where('cancel_by','!=',NULL)
                             ->orderBy('id','DESC');      

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
           $data = $data->where('cancel_by', 'like', '%' .$name .'%')
                        ->orwhere('pnr','like', '%' .$name . '%' );
        }     

        $data=$data->paginate($paginate);

         if($data){
            foreach($data as $key=>$v){

               $v['from_location']=$this->location->where('id', $v->source_id)->get();
               $v['to_location']=$this->location->where('id', $v->destination_id)->get();

               //   $stoppage = $this->bus->with('ticketPrice')->where('id', $v->bus_id)->get();
 
               // foreach ($stoppage[0]['ticketPrice'] as $k => $a) 
               //  {                          
               //      $stoppages['source'][$k]=$this->location->where('id', $a->source_id)->get();
               //      $stoppages['destination'][$k]=$this->location->where('id', $a->destination_id)->get(); 
               //  }
               //  $v['source']= $stoppages['source'];
               //  $v['destination']= $stoppages['destination'];
               }
           }


        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
        return $response; 
          
        
    }

}