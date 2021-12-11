<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\CouponAssignedBus;
use App\Models\CouponRoute;
use App\Models\CouponOperator;
use Illuminate\Support\Facades\Log;
class CouponRepository
{
    
    protected $coupon;
    protected $couponAssignedBus;
    protected $couponRoute;
    protected $couponOperator;
    
    public function __construct(Coupon $coupon, CouponAssignedBus $couponAssignedBus, CouponRoute $couponRoute, CouponOperator $couponOperator)
    {
        $this->coupon = $coupon;
        $this->couponAssignedBus = $couponAssignedBus;
        $this->couponRoute = $couponRoute;
        $this->couponOperator = $couponOperator;
    }

    
    public function getAll()
    {
        return $this->coupon->where('status','!=',2)->get();
    }

    
    public function getById($id)
    {
        return $this->coupon ->where('id', $id)->get();
    }

    public function saveCouponBus($data)
    {
        $couponBusData = new $this->couponAssignedBus;
        $couponBusData->coupon_id = $data['coupon_id'];
        $couponBusData->bus_id = $data['bus_id'];
        $couponBusData->created_by = $data['created_by'];
        $couponBusData->save();
        return $couponBusData->fresh();
    }

    public function saveCouponRoute($data)
    {
        $couponRouteData = new $this->couponRoute;
        $couponRouteData->coupon_id = $data['coupon_id'];
        $couponRouteData->source_id = $data['source_id'];
        $couponRouteData->destination_id = $data['destination_id'];
        $couponRouteData->created_by = $data['created_by'];
        $couponRouteData->save();
        return $couponRouteData->fresh();
    }

    public function saveCouponOperator($data)
    {
        $couponOperatorRecord = new $this->couponOperator;
        $couponOperatorRecord->coupon_id = $data['coupon_id'];
        $couponOperatorRecord->operator_id = $data['operator_id'];
        $couponOperatorRecord->created_by = $data['created_by'];
        $couponOperatorRecord->save();
        return $couponOperatorRecord->fresh();
    }



    public function save($data)
    {
        // Log::info($data);
        $coupons = new $this->coupon;
        $coupons->coupon_title = $data['coupon_title'];
        $coupons->coupon_code = $data['coupon_code'];
        $coupons->bus_operator_id = $data['bus_operator_id'];
        $coupons->type = $data['type'];
        $coupons->valid_by = $data['valid_by'];
        if($coupons->type==1)
        {
            $coupons->percentage = $data['percentage'];
            $coupons->max_discount_price = $data['max_discount_price'];
            $coupons->amount = 0;
            $coupons->min_tran_amount = 0;
           
        }
        else
        {
            $coupons->amount = $data['amount'];
            $coupons->min_tran_amount = $data['min_tran_amount'];
            $coupons->percentage = 0;
            $coupons->max_discount_price = 0;
        }
        
        
        $coupons->max_redeem = $data['max_redeem'];
        $coupons->from_date = $data['from_date'];
        $coupons->to_date = $data['to_date'];
        $coupons->short_desc = $data['short_desc'];
        $coupons->full_desc = $data['full_desc'];
        $coupons->created_by = $data['created_by'];
        $coupons->status = 1;
        
        $coupons->save();

        return $coupons->fresh();
    }



    
    public function update($data, $id)
    {
        $coupons = $this->coupon->find($id);
        
        // Log::info($data);
        $coupons->coupon_title = $data['coupon_title'];
        $coupons->coupon_code = $data['coupon_code'];
        $coupons->bus_operator_id = $data['bus_operator_id'];
        $coupons->type = $data['type'];
        $coupons->valid_by = $data['valid_by'];
        if($coupons->type==1)
        {
            $coupons->percentage = $data['percentage'];
            $coupons->max_discount_price = $data['max_discount_price'];
            $coupons->amount = 0;
            $coupons->min_tran_amount = 0;
           
        }
        else
        {
            $coupons->amount = $data['amount'];
            $coupons->min_tran_amount = $data['min_tran_amount'];
            $coupons->percentage = 0;
            $coupons->max_discount_price = 0;
        }
        
        
        $coupons->max_redeem = $data['max_redeem'];
        $coupons->from_date = $data['from_date'];
        $coupons->to_date = $data['to_date'];
        $coupons->short_desc = $data['short_desc'];
        $coupons->full_desc = $data['full_desc'];
        $coupons->created_by = $data['created_by'];
        

        $coupons->update();

        return $coupons;
    }

    public function getData($request)
    {
        // Log:: info($request);
        $name=$request->name;
        $paginate = $request->rows_number;
        $data= $this->coupon->with("BusOperator")->where('status','!=',2)->orderBy('id','DESC');
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($name))
        {
           $data=$data->whereHas('busOperator', function ($query) use ($name) {$query->where('operator_name',  'like', '%' .$name . '%' );})->orwhere('created_by', 'like', '%' .$name . '%');
        }

        $data=$data->paginate($paginate);     

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;      

    }

    
    public function delete($id)
    {       
        $coupons = $this->coupon->find($id);
        $coupons->status = 2;
        

        $coupons->update();

        return $coupons;

       
    }
    public function changeStatus($id)
    {
      $post = $this->coupon->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

}