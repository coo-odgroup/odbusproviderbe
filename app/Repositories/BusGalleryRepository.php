<?php

namespace App\Repositories;

use App\Models\BusGallery;
use Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File; 

class BusGalleryRepository
{
    /**
     * @var BusGallery
     */
    protected $busGallery;

    /**
     * BusGalleryRepository constructor.
     *
     * @param BusGallery $busGallery
     */
    public function __construct(BusGallery $busGallery)
    {
        $this->busGallery = $busGallery;
    }

    /**
     * Get all busGallery.
     *
     * @return BusGallery $busGallery
     */
    public function getAll()
    {
        return $this->busGallery
                    ->with('bus','busOperator')
                    ->whereNotIn('status', [2])
                    ->get();
    }

    /**
     * Get busGallery by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busGallery
            ->where('id', $id)
            ->get();
    }
    public function getByBusId($bid)
    {
        return $this->busGallery->whereNotIn('status', [2])
            ->where('bus_id', $bid)
            ->get();
    }

    public function viewBusGallery($request)
    {    
        $paginate = $request['rows_number'] ;
        $bus_id = $request['bus_id'] ;
        $bus_operator_id = $request['bus_operator_id'] ;

        $data= $this->busGallery->with('bus','busOperator')
                     ->whereNotIn('status', [2])
                     ->orderBy('id','DESC');
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->whereHas('bus', function ($query) use ($request){
               $query->where('bus_operator_id', $request['USER_BUS_OPERATOR_ID']);               
           });
        }
                     
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($bus_id!=null)
        {
            $data=$data->where('bus_id', $bus_id);
        } 
        if($bus_operator_id!=null)
        {
            $data=$data->where('bus_operator_id', $bus_operator_id);
        }
        $data=$data->paginate($paginate);

        return $data;
    }

    /**
     * Save busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function getModel($data, BusGallery $busGallery)
    {
        $busGallery->bus_id = $data['bus_id'];
        $busGallery->bus_operator_id = $data['bus_operator_id'];
        $busGallery->image = $data['icon'];
        $busGallery->created_by = $data['created_by'];
        return $busGallery;
    }

    public function save($data)
    {
        $busGallery = new $this->busGallery;
        $busGallery=$this->getModel($data,$busGallery);
        $file = collect($data)->get('icon');
        if(($file)!=null){

            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $busGallery->bus_image = $picture;
            $file->move(public_path('uploads/bus_photos/'), $picture);
       }
        $busGallery->save();
        return $busGallery;
    }

    /**
     * Update busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function update($data)
    {
        $busGalleryId = $data['id'];
        $gallery_data = $this->busGallery->where('id', $busGalleryId)->where('status','!=',2)->get();
   
        $busGallery = $this->busGallery->find($busGalleryId); 
        $file = collect($data)->get('icon');
      
        if(($file)!='null'){
            
            $busGallery = $this->getModel($data,$busGallery);
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = $filename;
            $busGallery->bus_image = $picture;
            $file->move(public_path('uploads/bus_photos/'), $picture);
          
            if($gallery_data[0]->bus_image!=''){
              
               $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image;
        
                if(File::exists($old_image_path_consumer)){
                        unlink($old_image_path_consumer);
                }
            }      
        }else{
             $busGallery=$this->getModel($data,$busGallery);
        }
        $busGallery->update();
        return $busGallery;
    }

    /**
     * Update busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function delete($id)
    {
        
        $post = $this->busGallery->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }

}