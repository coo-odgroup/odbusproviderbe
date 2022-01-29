<?php

namespace App\Repositories;

use App\Models\BusSeatLayout;
use App\Models\Bus;
use App\Models\BusSeats;
use App\Models\Seats;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class BusSeatLayoutRepository
{
    /**
     * @var BusSeatLayout
     */
    protected $busSeatLayout;
    protected $seats;
    protected $busSeats;

    /**
     * BusSeatLayoutRepository constructor.
     *
     * @param BusSeatLayout $busSeatLayout
     */
    public function __construct(BusSeatLayout $busSeatLayout, Bus $bus, Seats $seats, BusSeats $busSeats)
    {
        $this->busSeatLayout = $busSeatLayout;
        $this->bus = $bus;
        $this->seats = $seats;
        $this->busSeats= $busSeats;
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
    public function BusSeatLayoutbyUser($request)
    {
        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;
        $data= $this->busSeatLayout->where('status', "1");
        if($user_role==5)
        {
            $data= $data->where('user_id',$user_id);   
        } 

        return $data->get();
    }

    public function BusSeatLayoutOperator($request)
    {
        return $this->busSeatLayout
        ->whereNotIn('status', [0,2])
        ->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID'])
        ->get();
    }
    public function BusSeatLayoutData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;
       

        $data= $this->busSeatLayout->with('busOperator')->whereNotIn('status', [2])

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
            $data=$data->where('name', $name);
        } 
        if($user_role==5)
        {
            $data= $data->where('user_id',$user_id);   
        } 

        $data=$data->paginate($paginate);

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;
    }

    /**
     * Get busSeatLayout by id
     *
     * @param $id
     * @return mixed
     */
    public function getRowCol($id,$type)
    {
        return $this->seats->selectRaw("COUNT('colNumber') as tCol")
        ->groupBy('rowNumber')
        ->where('bus_seat_layout_id', $id)
        ->where('berthType', $type)
        ->get();

        //ADD CONDITIONS FOR Sleeper / Seater
    }
    public function getSeatLayoutRecord($id)
    {
        $seatData=[];

        $lowerBerth=$this->seats->with('BusSeats')
        ->where('bus_seat_layout_id',$id)
        ->where('berthType',1)
        ->where('status',1)
        ->get();


        foreach($lowerBerth as $key=>$rows)
        {
            $row_data=$this->seats
            ->where('rowNumber',$rows->rowNumber)
            ->where('berthType', '1')
            ->where('status',1)
            ->where('bus_seat_layout_id', $id)
            ->orderBy('colNumber')->get();
            $seatData['lowerBerth'][$rows->rowNumber]=$row_data;
        }

        $upperBerth=$this->seats->with('BusSeats')
        ->where('bus_seat_layout_id',$id)
        ->where('berthType',2)
        ->where('status',1)
        ->get();
        foreach($upperBerth as $key=>$rows)
        {
            $row_data=$this->seats->where('rowNumber',$rows->rowNumber)
            ->where('berthType', '2')
            ->where('bus_seat_layout_id', $id)
            ->where('status',1)
            ->orderBy('colNumber')->get();
            $seatData['upperBerth'][$rows->rowNumber]=$row_data;
        }
        return $seatData;
    }
    public function getById($id)
    {
       
        return $this->busSeatLayout->with('seats')
            ->where('id', $id)
            ->where('status', 1)
            ->get();
        
    }
    public function getModel($data, BusSeatLayout $busSeatLayout)
    {
        $busSeatLayout->name = $data['name'];
        $busSeatLayout->user_id = $data['user_id'];

        $busSeatLayout->bus_operator_id = $data['bus_operator_id'];
        $busSeatLayout->created_by =  $data['created_by'];
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
         $sLayoutContent=json_decode($data['layout_data'],true);

        
        $busSeatLayout = $this->busSeatLayout->find($id);
        $busSeatLayout = $this->getModel($data,$busSeatLayout);  
        $busSeatLayout->update();


        // $records=$this->seats->where('bus_seat_layout_id',$id)->get();
        // foreach ($records as $seat) {
        //     $seat->status = '2';
        //     $seat->save();
        // }


        $sLayoutContent=json_decode($data['layout_data'],true);

       // Log::info($sLayoutContent);

        foreach ($sLayoutContent as $ind_records) {

            

            $ind_records['seat_class_id']=$ind_records['seat_class_id'];
            $ind_records['bus_seat_layout_id']=$id;
            $ind_records['berthType']=$ind_records['berthType'];
            $ind_records['seatText']=(isset($ind_records['seatText'])) ? $ind_records['seatText'] : '';
            $ind_records['rowNumber']=$ind_records['rowNumber'];
            $ind_records['colNumber']=$ind_records['colNumber'];
            $ind_records['created_by']=$data['created_by'];

            $seats = $this->seats->find($ind_records['id']);
            $seats = $this->getSeatModel($ind_records,$seats);
            $seats->update();
        }
       // $busSeatLayout->seats()->saveMany($seatRecords);
       // return $busSeatLayout;
        return 'success';


    }

    public function getSeatModel($data,Seats $seats)
    {       
        $seats->seat_class_id = $data['seat_class_id'];
        $seats->bus_seat_layout_id = $data['bus_seat_layout_id'];
        $seats->berthType = $data['berthType'];
        $seats->seatText = $data['seatText'];
        $seats->rowNumber = $data['rowNumber'];   
        $seats->colNumber = $data['colNumber'];   
        $seats->created_by = $data['created_by'];  
        return $seats;
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