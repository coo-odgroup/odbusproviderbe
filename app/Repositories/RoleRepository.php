<?php
namespace App\Repositories;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
class RoleRepository
{
    /**
     * @var Role
     */
    protected $role;
    /**
     * BusSittingRepository constructor.
     *
     * @param BusSitting $busSitting
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    /**
     * Get all busSitting.
     *
     * @return BusSitting $busSitting
     */
    public function getAll()
    {
        return $this->role->whereNotIn('status', [2])->get();
    }

    public function RoleData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
       
        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;

        $data= $this->role->whereNotIn('status', [2])
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
            $data=$data->where('name','LIKE', '%'.$name.'%');
        } 
      
        if($user_role==5)
        {
            $data = $data->where('user_id',$user_id);   
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
     * Get busSitting by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->role->where('id', $id)->get();
    }
    /**
     * Save busSitting
     *
     * @param $data
     * @return BusSitting
     */
    public function save($data)
    {
        $role = new $this->role;
        $role->name = $data['name'];
        $role->created_by = $data['created_by'] ;
        $role->save();

        return $role->fresh();
    }

    
    public function update($data, $id)
    {
        $role = $this->role->find($id);
        $role->name = $data['name'];
        $role->created_by = $data['created_by'] ;
        $role->update();
        return $role;
    }
   
    public function delete($id)
    {
        $role = $this->role->find($id);
        $role->status = 2;
        $role->update();
        return $role;
    }


    ///////BusSittingType Data Table/////////////////////////
    public function getAllRoleDT($request)
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
        $totalRecords = $this->role->select('count(*) as allcount')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->role->select('count(*) as allcount')
        ->whereNotIn('status', [2])
        ->where('name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = $this->role->orderBy($columnName,$columnSortOrder)
            ->where('name', 'like', '%' .$searchValue . '%')
            ->whereNotIn('status', [2])
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $sno = $start+1;
        foreach($records as $record)
        {
            $id = $record->id;
            $name = $record->name;       
            $created_by = $record->created_by;
            $createdDate = $record->created_at;
            $updatedDate = $record->updated_at;    
            $status = $record->status;

            $data_arr[] = array(
                "id" => $id,
                "name" => $name,
                "created_by" => $created_by,
                "status" => $status,
                "created_at"=>date('j M Y h:i a',strtotime($createdDate)),
                "updated_at"=>date('j M Y h:i a',strtotime($updatedDate)),
            );
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
        $post = $this->role->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }   

}