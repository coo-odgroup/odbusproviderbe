<?php

namespace App\Repositories;

use App\Models\Banner;
use Illuminate\Support\Facades\Config;
class BannerRepository
{
    
    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
        $this->bus_operator_id = Config::get('constants.BUS_OPERATOR_ID');
    }

    public function getAllBanner()
    {
        return $this->banner->whereNotIn('status', [2])
                            ->where('bus_operator_id',$this->bus_operator_id)
                            ->get();
    }
    public function getData($request)
    {
        $paginate = $request['per_page'];
        $searchBy = $request['searchBy']; 
        $status = $request['status'];
       
        if($searchBy!='' && $status!=''){
            $list = $this->banner->where('occassion', 'like', '%' .$searchBy . '%')->where('bus_operator_id',$this->bus_operator_id)->where('status', $status)
                                 ->whereNotIn('status', [2])->orderBy('id','desc');
        }elseif($searchBy!='' && $status==''){
            $list = $this->banner->where('occassion', $searchBy)
                                 ->whereNotIn('status', [2])->where('bus_operator_id',$this->bus_operator_id)->orderBy('id','desc');
        }elseif($searchBy=='' && $status!=''){
            $list = $this->banner->where('status', $status)
                                 ->whereNotIn('status', [2])->where('bus_operator_id',$this->bus_operator_id)->orderBy('id','desc');
        }else{
            $list = $this->banner->whereNotIn('status', [2])->where('bus_operator_id',$this->bus_operator_id)->orderBy('id','desc');    
        }

        $list =  $list->paginate($paginate);
        //return $list;
        $response = array(
            "count" => $list->count(), 
            "total" => $list->total(),
            "data" => $list
           );   
           return $response;
                            
    }
    public function getById($id)
    {
        return $this->banner
            ->where('id', $id)
            ->where('bus_operator_id',$this->bus_operator_id)
            ->get();
    }

    public function save($data)
    {
        $bann = new $this->banner;
        $bann->occassion = $data['occassion'];
        $bann->category = $data['category'];
        $bann->url = $data['url'];
        $bann->banner_img = $data['banner_img'];
        $bann->alt_tag = $data['alt_tag'];
        $bann->start_date = $data['start_date'];
        $bann->start_time = $data['start_time'];
        $bann->end_date = $data['end_date'];
        $bann->end_time = $data['end_time'];
        $bann->created_by = "Admin";
        $bann->bus_operator_id = $this->bus_operator_id;
        $bann->save();

        return $bann->fresh();
    }

    public function update($data, $id)
    {
        $bann = $this->banner->find($id);
        $bann->bus_operator_id = $this->bus_operator_id;
        $bann->occassion = $data['occassion'];
        $bann->url = $data['url'];
        $bann->banner_img = $data['banner_img'];
        $bann->alt_tag = $data['alt_tag'];
        $bann->start_date = $data['start_date'];
        $bann->start_time = $data['start_time'];
        $bann->end_date = $data['end_date'];
        $bann->end_time = $data['end_time'];
        $bann->update();
        return $bann;
    }

    public function delete($id)
    {
        $bann = $this->banner->where('bus_operator_id',$this->bus_operator_id)->find($id);
        $bann->status = 2;
        $bann->update();

        return $bann;
    }
    public function changeStatus($id)
    {
        $bann = $this->banner->where('bus_operator_id',$this->bus_operator_id)->find($id);
        if($bann->status==0){
            $bann->status = 1;
        }elseif($bann->status==1){
            $bann->status = 0;
        }
        $bann->update();
        return $bann;
    }

}