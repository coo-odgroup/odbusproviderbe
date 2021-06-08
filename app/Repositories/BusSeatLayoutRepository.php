<?php

namespace App\Repositories;

use App\Models\BusSeatLayout;
use App\Models\Bus;
use App\Models\Seats;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class BusSeatLayoutRepository
{
    /**
     * @var BusSeatLayout
     */
    protected $busSeatLayout;
    protected $Seats;

    /**
     * BusSeatLayoutRepository constructor.
     *
     * @param BusSeatLayout $busSeatLayout
     */
    public function __construct(BusSeatLayout $busSeatLayout, Bus $bus, Seats $Seats)
    {
        $this->busSeatLayout = $busSeatLayout;
        $this->bus = $bus;
        $this->Seats = $Seats;
    }

    /**
     * Get all busSeatLayout.
     *
     * @return BusSeatLayout $busSeatLayout
     */
    public function getAll()
    {
        return $this->busSeatLayout
        ->whereNotIn('status', [0,2])
        ->get();
    }

    /**
     * Get busSeatLayout by id
     *
     * @param $id
     * @return mixed
     */
    public function getRowCol($id,$type)
    {
        return $this->Seats->selectRaw("COUNT('colNumber') as tCol")
        ->groupBy('rowNumber')
        ->where('bus_seat_layout_id', $id)
        ->where('berthType', $type)
        ->get();

        //ADD CONDITIONS FOR Sleeper / Seater
    }
    public function getSeatLayoutRecord($id)
    {
        $seatData=[];

        $seatData['layoutData']=$this->busSeatLayout->where('id', $id)->get();

        $lowerBerth=$this->Seats
        ->distinct('rowNumber')
        ->where('bus_seat_layout_id', $id)
        ->where('berthType', '1')
        ->orderBy('rowNumber')
        ->get();
       
        foreach($lowerBerth as $key=>$rows)
        {
            $row_data=$this->Seats->where('rowNumber',$rows->rowNumber)->where('berthType', '1')->where('bus_seat_layout_id', $id)->orderBy('colNumber')->get();
            $seatData['lowerBerth'][$rows->rowNumber]=$row_data;
        }
        $upperBerth=$this->Seats
        ->distinct('rowNumber')
        ->where('bus_seat_layout_id', $id)
        ->where('berthType', '2')
        ->orderBy('rowNumber')
        ->get();
        foreach($upperBerth as $key=>$rows)
        {
            $row_data=$this->Seats->where('rowNumber',$rows->rowNumber)->where('berthType', '2')->where('bus_seat_layout_id', $id)->orderBy('colNumber')->get();
            $seatData['upperBerth'][$rows->rowNumber]=$row_data;
        }
        return $seatData;
    }
    public function getById($id)
    {
       
        return $this->busSeatLayout->with('seats')
            ->where('id', $id)
            
            ->get();
        
    }
    public function getModel($data, BusSeatLayout $busSeatLayout)
    {
        $busSeatLayout->name = $data['name'];
        $busSeatLayout->created_by = "Admin";
        return $busSeatLayout;
    }
    /**
     * Save busSeatLayout
     *
     * @param $data
     * @return BusSeatLayout
     */
    public function save($data)
    {
        $busSeatLayout = new $this->busSeatLayout;
        $busSeatLayout = $this->getModel($data,$busSeatLayout);
        $busSeatLayout->save();

        $sLayoutContent=json_decode($data['layout_data'],true);
        $seatRecords = [];
        foreach ($sLayoutContent as $ind_records) {
            $ind_records['seat_class_id']=$ind_records['seatType'];
            $seatRecords[] =new Seats($ind_records);
        }
        $busSeatLayout->seats()->saveMany($seatRecords);
        return $busSeatLayout;
    }

    /**
     * Update busSeatLayout
     *
     * @param $data
     * @return BusSeatLayout
     */
    public function update($data, $id)
    {
        $busSeatLayout = $this->busSeatLayout->find($id);
        $busSeatLayout = $this->getModel($data,$busSeatLayout);
        $busSeatLayout->update();
        $this->Seats->where('bus_seat_layout_id',$id)->delete();

        $sLayoutContent=json_decode($data['layout_data'],true);
        $seatRecords = [];
        foreach ($sLayoutContent as $ind_records) {
            $seatRecords[] =new Seats($ind_records);
        }
        $busSeatLayout->seats()->saveMany($seatRecords);
        return $busSeatLayout;


    }
    /**
     * Update busSeatLayout
     *
     * @param $data
     * @return BusSeatLayout
     */
    public function delete($id)
    {
        $post = $this->busSeatLayout->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }
    ///////BusSittingType Data Table/////////////////////////
    public function getAllBusSeatLayoutDT($request)
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
        $totalRecords = $this->busSeatLayout->select('count(*) as allcount')
        ->whereNotIn('status', [2])
        ->count();
        $totalRecordswithFilter = $this->busSeatLayout->select('count(*) as allcount')
        ->whereNotIn('status', [2])
        ->where('name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = $this->busSeatLayout->orderBy($columnName,$columnSortOrder)
            ->where('name', 'like', '%' .$searchValue . '%')
            ->Where('status','!=','2')
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
        return $response;
        
    }
    public function changeStatus($id)
    {
        $post = $this->busSeatLayout->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    
    


}