<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\BusType;
class BusTypeRepository
{
    /**
     * @var BusType
     */
    protected $busType;

    /**
     * BusTypeRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(BusType $busType)
    {
        $this->busType = $busType;
    }

    
    public function getAll($request)
    {
        return $this->busType->get();

    }
    public function getModel($data, BusType $busType)
    {
        $busType->type = $data['Type'];
        $busType->name = $data['Name'];    
        $busType->created_by = "Admin";
        $busType->status = 0;
        return $busType;
    }
    
    public function getById($id)
    {
        return $this->busType->where('id', $id)->get();
    }
    public function save($data)
    {
        $busType = new $this->busType;
        $busType=$this->getModel($data,$busType);
        $busType->save();
        return $busType;
    }

    /**
     * Update BusType
     *
     * @param $data
     * @return Post
     */
    public function update($data, $id)
    {
        $busType = $this->busType->find($id);
        $busType=$this->getModel($data,$busType);
        $busType->update();
        return $busType;
    }

    /**
     * Delete BusType
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        //$post = $this->busType->where('status',"0")->orWhere('status',"1")->findOrFail($id);
        $post = $this->busType->find($id);
        $post->status = 2;
        $post->update();
        return $post;

    }

    //BusType Data Table
    public function getAllBusTypeDT($request)   
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

        // Total records
        $totalRecords = $this->busType->select('COUNT(*) as allcount')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->busType
        ->where('name', 'like', "%" .$searchValue . "%")->whereNotIn('status', [2])
        ->count();

        // Fetch records
        $records = $this->busType->orderBy($columnName,$columnSortOrder)
            ->where('name', 'like', '%' .$searchValue . '%')
            ->whereNotIn('status', [2])
            ->orWhere(function($query) use ($searchValue)
            {
                if($searchValue=="AC")
                {
                    $query->orWhere('type','0');
                }
                if($searchValue=="NON AC")
                {
                    $query->orWhere('type','1');
                }
            })
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $typename = array(
            "1" => "AC",
            "2" => "NON AC"
        );
        foreach($records as $key=>$record)
        {
            $data_arr[]=$record->toArray();
            $data_arr[$key]['created_at']=date('j M Y h:i a',strtotime($record->created_at));
            $data_arr[$key]['updated_at']=date('j M Y h:i a',strtotime($record->updated_at));
            $data_arr[$key]['type']=$typename[$record->bus_class_id];
            
        }    
        

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ); 
        return $response;
        
    }

    public function changeStatus($id)
    {
        $post = $this->busType->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
}