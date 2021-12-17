<?php

namespace App\Repositories;
use App\Models\Safety;
use App\Models\BusSafety;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class SafetyRepository 
{
    protected $safety;
    protected $busSafety;
    public function __construct(Safety $safety,BusSafety $busSafety)
    {
        $this->safety = $safety;
        $this->busSafety =$busSafety;
    }
    public function getAll()
    {
        return $this->safety->whereNotIn('status', [2])->get();
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
        $totalRecords = $this->safety
        ->whereNotIn('status', [2])
        ->count();
        $totalRecordswithFilter = $this->safety
        ->where('name', 'like', "%" .$searchValue . "%")
        ->whereNotIn('status', [2])
        ->count();
        
        $records = $this->safety->orderBy($columnName,$columnSortOrder)
            ->where('name', "like", "%" .$searchValue . "%")
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
    public function getAllData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;
        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;
       

        $data= $this->safety->whereNotIn('status', [2])
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

            $data = $data->where(
                function($query) use ($name) {
                    $data = $query->where('name', 'like', '%' .$name . '%')
                    ->orWhere('bus_number', 'like', '%' .$name . '%')
                    ->orWhere('via', 'like', '%' .$name . '%')
                    ->orWhere('created_by', 'like', '%' .$name . '%')
                    ->orwhereHas('busOperator', function ($query) use ($name)
                                {$query->where('organisation_name','like', '%' .$name . '%' );})
                   ->orwhereHas('busOperator', function ($query) use ($name)
                               {$query->where('operator_name', 'like', '%' .$name . '%');});
            });           

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

    public function getById($id)
    {
        return $this->safety
            ->where('id', $id)
            ->get();
    }
    public function getByBusId($id)
    {
        return $this->busSafety
            ->where('bus_id', $id)
            ->get();
    }
    public function getModel($data, Safety $safety)
    {
        $safety->name = $data['name'];
        $safety->icon = "";
        $safety->created_by = $data['created_by'];
        $safety->user_id = $data['user_id'];
        return $safety;
    }
    /**
     * Save safety
     *
     * @param $data
     * @return safety
     */
    public function save($data)
    {
        // Log::info($data);exit;
   
        $duplicate_data = $this->safety
                               ->where('name',$data['name'])
                               ->where('status','!=',2)
                               ->where('user_id',$data['user_id'])
                               ->get();
        if(count($duplicate_data)==0)
        {
            $safetyObject = new $this->safety;
            $safety=$this->getModel($data,$safetyObject);

            $webfile = collect($data)->get('icon');     
            if(($webfile)!=null){
                $filename  = $webfile->getClientOriginalName();
                $extension = $webfile->getClientOriginalExtension();
                $webPicture   =  rand().'-'.$filename;
                $safety->safety_image = $webPicture;
                $webfile->move(public_path('uploads/safety/'), $webPicture);
           }
           $androidfile = collect($data)->get('android_image');     
            if(($androidfile)!=null){
                $filename  = $androidfile->getClientOriginalName();
                $extension = $androidfile->getClientOriginalExtension();
                $androidPicture   =  rand().'-'.$filename;
                $safety->android_image = $androidPicture;
                $androidfile->move(public_path('uploads/safety/'), $androidPicture);
           }

            $safety->save();
            return $safety;
        }
        else
        {
             return 'Safety Already Exist';
        }
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function update($data)
    {
       
        $id = $data['id'] ;      
        $duplicate_data = $this->safety
                               ->where('name',$data['name'])
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {  
            $safety_detail  = $this->safety->where('id', $id)->get();
            $existing_image = $safety_detail[0]->safety_image;

            $safety = $this->safety->find($id);
            $webfile = collect($data)->get('icon');
            if($webfile!=null)
            {
                
                $safety=$this->getModel($data,$safety);
                $filename  = $webfile->getClientOriginalName();
                $extension = $webfile->getClientOriginalExtension();
                $picture =  rand().'-'.$filename;
                $safety->safety_image =  $picture;
           
                $webfile->move(public_path('uploads/safety/'), $picture);
             

                $old_image_path_consumer = public_path('uploads/safety/').$existing_image;
             

               if($safety_detail[0]->safety_image!='')
                {
                    if(File::exists($old_image_path_consumer))
                 {
                        unlink($old_image_path_consumer);
                      
                 }  
                } 
                       
            }
            $file = collect($data)->get('android_image');
            if($file!= null)
            {   
                $safety=$this->getModel($data,$safety);
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture =  rand().'-'.$filename;
                $safety->android_image =  $picture;
           
                $file->move(public_path('uploads/safety/'), $picture);
             

                $old_image_path_consumer = public_path('uploads/safety/').$safety_detail[0]->android_image;
             

               if($safety_detail[0]->android_image!='')
                {
                    if(File::exists($old_image_path_consumer))
                 {
                        unlink($old_image_path_consumer);
                      
                 }  
                } 
                       
            }
            else
            {
                 $safety=$this->getModel($data,$safety);
            }
            $safety->update();
            return $safety;
        }
        else
        {
            return 'Safety Already Exist';
        } 
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function delete($id)
    {
        $safety = $this->safety->find($id);
        $safety->status = 2;
        $safety->update();
        return $safety;
    }

    public function changeStatus($data,$id)
    {
        $safety = $this->safety->find($id);
        if($safety->status==0){
            $safety->status = 1;
        }elseif($safety->status==1){
            $safety->status = 0;
        }
        $safety->update();
        return $safety;
    }

}