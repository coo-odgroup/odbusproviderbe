<?php
namespace App\Repositories;
use App\Models\OwnerPayment;
use App\Models\Bus;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class OwnerPaymentRepository
{
  
    protected $ownerPayment;
    protected $booking;
    
    public function __construct(OwnerPayment $ownerPayment,Booking $booking)
    {
        $this->ownerPayment = $ownerPayment;
        $this->booking = $booking;  
    }

    public function getAll()
    {
       
        return $this->ownerPayment->with('busOperator')->whereNotIn('status', [2])->get();
    }
    
    public function getDatatable($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        if(!is_numeric($rowperpage))
        {
            $rowperpage=Config::get('constants.ALL_RECORDS');
        }

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = $this->ownerPayment->with('busOperator')
        ->whereNotIn('status', [2])
        ->count();
        $totalRecordswithFilter = $this->ownerPayment->with('busOperator')
        ->whereHas('busOperator', function ($query) use ($searchValue){
               $query->where('operator_name', 'like', '%' .$searchValue . '%');               
           })
        //->where('name', 'like', "%" .$searchValue . "%")
        ->whereNotIn('status', [2])
        ->count();
        
        $records = $this->ownerPayment->with('busOperator')->orderBy($columnName,$columnSortOrder)
            ->whereHas('busOperator', function ($query) use ($searchValue){
               $query->where('operator_name', 'like', '%' .$searchValue . '%');               
           })
            ->whereNotIn('status', [2])
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $data_arr = array();
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
        }    
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return ($response);
     
    }   
        
    public function getModel($data, OwnerPayment $ownerPayment)
    {
        $ownerPayment->bus_operator_id = $data['bus_operator_id'];
        $ownerPayment->start_date = $data['startDate'];
        $ownerPayment->end_date = $data['endDate'];
        $ownerPayment->total_seat = $data['noSeat'];
        $ownerPayment->total_pnr = $data['noPnr'];
        $ownerPayment->amount = $data['amount'];
        $ownerPayment->transaction_id = $data['transaction_id'];
        $ownerPayment->remark = $data['remark'];
        $ownerPayment->payment_note = $data['paymentNote'];
        $ownerPayment->created_by = $data['created_by'];
        $ownerPayment->status = 0;
        return $ownerPayment;
    }

    public function save($data)
    {
        $ownerPayment = new $this->ownerPayment;
        $ownerPayment=$this->getModel($data,$ownerPayment);
        $ownerPayment->save();       
        return $ownerPayment;
    }


    public function getPaymentDetails($data)
    {

        // log::info($data);
        $bus_operator_id = $data->operatorId;
        $start_date  =  $data->startDate;
        $end_date  =  $data->endDate;

        $dt= $this->booking->whereHas('bus.busOperator', function ($query) use ($bus_operator_id) 
                      {$query->where('id', $bus_operator_id );})
                             ->whereBetween('journey_dt', [$start_date, $end_date])
                             ->where('status',1)->get();

        $owner_fare = 0;
        $totalSeats = 0;
        if($dt){
            foreach($dt as $key=>$v){
              $totalSeats = $totalSeats +  count($v->BookingDetail);
               $owner_fare = $owner_fare + $v->owner_fare;               
            }
        }
         $response = array(
             "count" => $dt->count(), 
             "totalSeats" => $totalSeats,
             "owner_fare"=>number_format($owner_fare, 2, ".", ""),
           );  
        
        return $response;    
        
    }
    

    public function ownerpaymentData($request)
    {
        // log::info($request);

         $paginate = $request['rows_number'] ;
         $bus_operator_id = $request['bus_operator_id'] ;
         $fromDate = $request['fromDate'] ;
         $toDate = $request['toDate'] ;
         // $name = $request['name'] ;

        $data= $this->ownerPayment->with('busOperator')
                    ->whereNotIn('status', [2])->orderBy('id','DESC');


        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($bus_operator_id != null){
            $data = $data->where('bus_operator_id',$bus_operator_id);
        }


         if($fromDate != null && $toDate != null){
             if($fromDate == $toDate){
                $data =$data->where('created_at', 'like','%'.$fromDate.'%')
                        ->orderBy('created_at','DESC');
            }else{
                 $data =$data-> whereBetween('created_at', [$fromDate, $toDate])
                        ->orderBy('created_at','DESC');
            }
        }   

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;  

    }
 
}