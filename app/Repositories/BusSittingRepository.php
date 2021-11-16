<?php
namespace App\Repositories;
use App\Models\BusSitting;
use Illuminate\Support\Facades\Log;
class BusSittingRepository
{
    /**
     * @var BusSitting
     */
    protected $busSitting;
    /**
     * BusSittingRepository constructor.
     *
     * @param BusSitting $busSitting
     */
    public function __construct(BusSitting $busSitting)
    {
        $this->busSitting = $busSitting;
    }
    /**
     * Get all busSitting.
     *
     * @return BusSitting $busSitting
     */
    public function getAll()
    {
        return $this->busSitting->whereNotIn('status', [2])->get();
    }

      public function BusSittingData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
       

        $data= $this->busSitting->whereNotIn('status', [2])
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
            $data=$data->where('name', $name);
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
        return $this->busSitting->where('id', $id)->get();
    }
    /**
     * Save busSitting
     *
     * @param $data
     * @return BusSitting
     */
    public function save($data)
    {
        $busSitting = new $this->busSitting;
        $busSitting->name = $data['name'];
        $busSitting->created_by = $data['created_by'] ;
        $busSitting->save();

        return $busSitting->fresh();
    }

    /**
     * Update busSitting
     *
     * @param $data
     * @return BusSitting
     */
    public function update($data, $id)
    {
        $busSitting = $this->busSitting->find($id);
        $busSitting->name = $data['name'];
         $busSitting->created_by = $data['created_by'] ;
        $busSitting->update();
        return $busSitting;
    }
    /**
     * Update busSitting
     *
     * @param $data
     * @return BusSitting
     */
    public function delete($id)
    {
        $busSitting = $this->busSitting->find($id);
        $busSitting->status = 2;
        $busSitting->update();
        return $busSitting;
    }


    ///////BusSittingType Data Table/////////////////////////
    public function getAllBusSittingDT($request)
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
        $totalRecords = $this->busSitting->select('count(*) as allcount')->whereNotIn('status', [2])->count();
        $totalRecordswithFilter = $this->busSitting->select('count(*) as allcount')
        ->whereNotIn('status', [2])
        ->where('name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = $this->busSitting->orderBy($columnName,$columnSortOrder)
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
        $post = $this->busSitting->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }
    

}