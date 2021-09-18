<?php
namespace App\Repositories;
use App\Models\OwnerPayment;
use App\Models\Bus;
use Illuminate\Support\Facades\Log;

class OwnerPaymentRepository
{
  
    protected $ownerPayment;
    
    public function __construct(OwnerPayment $ownerPayment)
    {
        $this->ownerPayment = $ownerPayment;
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
        $ownerPayment->payment_date = $data['date'];
        $ownerPayment->amount = $data['amount'];
        $ownerPayment->transaction_id = $data['transaction_id'];
        $ownerPayment->remark = $data['remark'];
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
    
 
}