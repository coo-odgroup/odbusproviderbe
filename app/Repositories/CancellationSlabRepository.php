<?php

namespace App\Repositories;

use App\Models\CancellationSlab;
use App\Models\CancellationSlabInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
class CancellationSlabRepository
{
    protected $cancellationSlab;
    protected $cancellationSlabInfo;
    public function __construct(CancellationSlab $cancellationSlab, CancellationSlabInfo $cancellationSlabInfo)
    {
        $this->cancellationSlab = $cancellationSlab;
        $this->cancellationSlabInfo = $cancellationSlabInfo;
    }
    public function getAll($request)
    {
        return $this->cancellationSlab->with('SlabInfo') ->get();
    }

    public function cancellationslabData($request)
    {      
         $paginate = $request['rows_number'] ;
         $name = $request['name'] ;
       

        $data= $this->cancellationSlab->with('SlabInfo')->with('busOperator')->whereNotIn('status', [2])
                             ->orderBy('id','DESC');
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }                        

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
            $data = $data->where('rule_name','like', '%' .$name . '%');
        } 
      

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;


    }
    public function getCancellationSlabDT($request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        if(!is_numeric($rowperpage))
        {
            $rowperpage=10000;
        }

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $totalRecords = $this->cancellationSlab->where('status','!=','2')->count();
        $totalRecordswithFilter = $this->cancellationSlab->with('SlabInfo') 
        ->where('rule_name', 'like', "%" .$searchValue . "%")
        ->where('status','!=','2')
        ->count();
        $records = $this->cancellationSlab->with('SlabInfo') 
            ->orderBy($columnName,$columnSortOrder)
            ->where('rule_name', "like", "%" .$searchValue . "%")
            ->where('status','!=','2')
            ->skip($start)
            ->take($rowperpage)
            ->get();
        $data_arr = array();
        foreach($records as $record)
        {
            $data_arr[]=$record->toArray();
        }
        $api_array=array("1"=>"ODBUS");
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return ($response);
    }

    /**
     * Get cancellation Slab by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        //->SlabInfo()
        return $this->cancellationSlab->with('SlabInfo') 
            ->where('id', $id)
            ->get();
    }

    public function getModel($data, CancellationSlab $cSlab)
    {
        $cSlab->bus_operator_id = $data['bus_operator_id'];
        $cSlab->rule_name = $data['rule_name'];
        $cSlab->cancellation_policy_desc = $data['cancellation_policy_desc'];
        $cSlab->created_by = $data['created_by'];
        $cSlab->status =0;
        return $cSlab;
    }
    /**
     * Save Slab
     *
     * @param $data
     * @return cancellationSlab
     */
    public function save($data)
    {
        $cSlab = new $this->cancellationSlab;
        $cSlab=$this->getModel($data,$cSlab);
        $cSlab->save();


        $cSlabDetails=[];
        $slabs=$data['slabs'];
        foreach($slabs as $slab_data)
        {
            $cSlabInfo=new CancellationSlabInfo();
            $cSlabInfo->duration=$slab_data['duration'];
            $cSlabInfo->deduction=$slab_data['deduction'];
            $cSlabInfo->created_by = $data['created_by'];
            $cSlabInfo->status='1';
            $cSlabDetails[]=$cSlabInfo;
        }
        $cSlab->SlabInfo()->saveMany($cSlabDetails);


        return $cSlab;
    }

    /**
     * Update cancellationSlab
     *
     * @param $data
     * @return cancellationSlab
     */
    public function update($data, $id)
    { 
        $cSlab = $this->cancellationSlab->findOrFail($id);
        $cSlab=$this->getModel($data,$cSlab);
        $cSlab->update();

        $cancellationSlabInforecord=$this->cancellationSlabInfo->where('cancellation_slab_id',$id);
        $cancellationSlabInforecord->delete();

        $cSlabDetails=[];
        $slabs=$data['slabs'];
        foreach($slabs as $slab_data)
        {
            $cSlabInfo=new CancellationSlabInfo();
            $cSlabInfo->duration=$slab_data['duration'];
            $cSlabInfo->deduction=$slab_data['deduction'];
            $cSlabInfo->created_by = $data['created_by'];
            $cSlabInfo->status='1';
            $cSlabDetails[]=$cSlabInfo;
        }
        $cSlab->SlabInfo()->saveMany($cSlabDetails);


        return $cSlab;
    }

    /**
     * Update cancellationSlab
     *
     * @param $data
     * @return cancellationSlab
     */
    public function delete($id)
    {
        $cSlab = $this->cancellationSlab->find($id);
        $cSlab->status = 2;
        $cSlab->update();
        return $cSlab;
    }

    public function changeStatus($id)
    {
        $cSlab = $this->cancellationSlab->find($id);
        if($cSlab->status==0){
            $cSlab->status = 1;
        }elseif($cSlab->status==1){
            $cSlab->status = 0;
        }
        $cSlab->update();
        return $cSlab;
    }

}