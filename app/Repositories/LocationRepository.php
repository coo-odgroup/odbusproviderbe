<?php
namespace App\Repositories;
use App\Models\Location;
use App\Models\Locationcode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class LocationRepository
{
    /**
     * @var Location
     */
    protected $location;
    protected $locationcode;

    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(Location $location, Locationcode $locationcode)
    {
        $this->location = $location;
        $this->locationcode = $locationcode;
    }
    public function getAll()
    {
        return $this->location::with('locationcode')->orderBy('name','ASC')->where('status','1')->get();
    }
    public function getById($id)
    {
        return $this->location::with('locationcode')->where('id', $id)->get();
    }
    public function delete($id)
    {
        $location = $this->location->find($id);
        $location->status = 2;
        $location->update();
        return $location;
    }
    public function getModel($data, Location $location)
    {
      $location->name = $data['name'];
      $location->synonym = $data['synonym'];
      $location->created_by = $data['created_by'];
      return $location;
    }
    public function add($data)
    {
        $location = new $this->location;
        $location=$this->getModel($data,$location);
        $location->save();
        return $location;
    }

    public function edit($data, $id)
    {
        $location = $this->location->find($id);
        $location=$this->getModel($data,$location);
        $location->update();
        return $location;
    } 



    public function locationsData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->location->whereNotIn('status', [2])->orderBy('id','DESC');


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
            $data = $data->where(function($query) use ($name) {
                        $query->where('name','like', '%' .$name . '%')
                        ->orWhere('synonym','like', '%' .$name . '%');
                    });                             
        }     
        $data=$data->paginate($paginate);
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "test" => "hello",
            "data" => $data
           );   
           return $response; 
    }
    public function getAllLocationDT( $request)
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
        $totalRecords = $this->location->select('count(*) as allcount')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->location->select('count(*) as allcount')->where('name', 'like', '%' .$searchValue . '%')->whereNotIn('status', [2])->count();

        // Fetch records
        $records = $this->location->orderBy($columnName,$columnSortOrder)
            ->where(function($query) use ($searchValue){
              $query->where('name', 'like', '%' .$searchValue . '%');
               $query->orWhere('synonym', 'like', '%' .$searchValue . '%');
              })
            ->where('status','!=', 2)
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
        $location = $this->location->find($id);
        if($location->status==0){
            $location->status = 1;
        }elseif($location->status==1){
            $location->status = 0;
        }
        $location->update();
        return $location;
    }

}