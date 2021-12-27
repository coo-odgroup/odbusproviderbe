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
        $busGallery->image = '';
        $busGallery->created_by = $data['created_by'];
        return $busGallery;
    }

    public function save($data)
    {
        $duplicate_data = $this->busGallery
                               ->where('bus_id',$data['bus_id'])
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {
            $busGallery = new $this->busGallery;
            $busGallery=$this->getModel($data,$busGallery);
            $gallery_img_1 = collect($data)->get('bus_image_1');     
                if(($gallery_img_1)!=null){
                    $filename  = $gallery_img_1->getClientOriginalName();
                    $extension = $gallery_img_1->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_1 = $picture;
                    $gallery_img_1->move(public_path('uploads/bus_photos/'), $picture);
               }
            $gallery_img_2 = collect($data)->get('bus_image_2');     
                if(($gallery_img_2)!=null){
                    $filename  = $gallery_img_2->getClientOriginalName();
                    $extension = $gallery_img_2->getClientOriginalExtension();
                    $picture_2   =  rand().'-'.$filename;
                    $busGallery->bus_image_2 = $picture_2;
                    $gallery_img_2->move(public_path('uploads/bus_photos/'), $picture_2);
               }
            $gallery_img_3 = collect($data)->get('bus_image_3');     
                if(($gallery_img_3)!=null){
                    $filename  = $gallery_img_3->getClientOriginalName();
                    $extension = $gallery_img_3->getClientOriginalExtension();
                    $picture_3   =  rand().'-'.$filename;
                    $busGallery->bus_image_3 = $picture_3;
                    $gallery_img_3->move(public_path('uploads/bus_photos/'), $picture_3);
               }
            $gallery_img_4 = collect($data)->get('bus_image_4');     
                if(($gallery_img_4)!=null){
                    $filename  = $gallery_img_4->getClientOriginalName();
                    $extension = $gallery_img_4->getClientOriginalExtension();
                    $picture_4   =  rand().'-'.$filename;
                    $busGallery->bus_image_4 = $picture_4;
                    $gallery_img_4->move(public_path('uploads/bus_photos/'), $picture_4);
               }
            $gallery_img_5 = collect($data)->get('bus_image_5');     
                if(($gallery_img_5)!=null){
                    $filename  = $gallery_img_5->getClientOriginalName();
                    $extension = $gallery_img_5->getClientOriginalExtension();
                    $picture_5   =  rand().'-'.$filename;
                    $busGallery->bus_image_5 = $picture_5;
                    $gallery_img_5->move(public_path('uploads/bus_photos/'), $picture_5);
               }

            $busGallery->save();
            return $busGallery;
        }
         else
        {
             return 'Bus Already Exist';
        }
        
    }

    /**
     * Update busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function update($data)
    {
        $id = $data['id'] ;      
        $duplicate_data = $this->busGallery
                               ->where('bus_id',$data['bus_id'])
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {
            $busGalleryId = $data['id'];
            $gallery_data = $this->busGallery->where('id', $busGalleryId)->where('status','!=',2)->get();
       
            $busGallery = $this->busGallery->find($busGalleryId); 

            $gallery_img_1 = collect($data)->get('bus_image_1');
          
            if($gallery_img_1!= null)
            {
                    $filename  = $gallery_img_1->getClientOriginalName();
                    $extension = $gallery_img_1->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_1 = $picture;
                    $gallery_img_1->move(public_path('uploads/bus_photos/'), $picture);
              
                if($gallery_data[0]->bus_image_1!=''){                  
                   $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image_1;
            
                    if(File::exists($old_image_path_consumer)){
                            unlink($old_image_path_consumer);
                    }
                }      
            }
            
            $gallery_img_2 = collect($data)->get('bus_image_2');
            if($gallery_img_2!= null)
            {
                    $filename  = $gallery_img_2->getClientOriginalName();
                    $extension = $gallery_img_2->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_2 = $picture;
                    $gallery_img_2->move(public_path('uploads/bus_photos/'), $picture);
              
                if($gallery_data[0]->bus_image_2!=''){                  
                   $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image_2;
            
                    if(File::exists($old_image_path_consumer)){
                            unlink($old_image_path_consumer);
                    }
                }      
            }
            $gallery_img_3 = collect($data)->get('bus_image_3');
            if($gallery_img_3!= null)
            {
                    $filename  = $gallery_img_3->getClientOriginalName();
                    $extension = $gallery_img_3->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_3 = $picture;
                    $gallery_img_3->move(public_path('uploads/bus_photos/'), $picture);
              
                if($gallery_data[0]->bus_image_3!=''){                  
                   $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image_3;
            
                    if(File::exists($old_image_path_consumer)){
                            unlink($old_image_path_consumer);
                    }
                }      
            }

            $gallery_img_4 = collect($data)->get('bus_image_4');
            if($gallery_img_4!= null)
            {
                    $filename  = $gallery_img_4->getClientOriginalName();
                    $extension = $gallery_img_4->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_4 = $picture;
                    $gallery_img_4->move(public_path('uploads/bus_photos/'), $picture);
              
                if($gallery_data[0]->bus_image_4!=''){                  
                   $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image_4;
            
                    if(File::exists($old_image_path_consumer)){
                            unlink($old_image_path_consumer);
                    }
                }      
            }
            $gallery_img_5 = collect($data)->get('bus_image_5');
            if($gallery_img_5!= null)
            {
                    $filename  = $gallery_img_5->getClientOriginalName();
                    $extension = $gallery_img_5->getClientOriginalExtension();
                    $picture   =  rand().'-'.$filename;
                    $busGallery->bus_image_5 = $picture;
                    $gallery_img_5->move(public_path('uploads/bus_photos/'), $picture);
              
                if($gallery_data[0]->bus_image_5!=''){                  
                   $old_image_path_consumer = public_path('uploads/bus_photos/').$gallery_data[0]->bus_image_5;
            
                    if(File::exists($old_image_path_consumer)){
                            unlink($old_image_path_consumer);
                    }
                }      
            }
            else
            {
                $busGallery=$this->getModel($data,$busGallery);
            }
            $busGallery->update();
            return $busGallery;
        }
        else
        {
             return 'Bus Already Exist';
        }   
        
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