<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\CouponAssignedBus;
use App\Models\CouponRoute;
use App\Models\CouponOperator;
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
        $coupons = new $this->coupon;
        $coupons->coupon_title = $data['coupon_title'];
        $coupons->coupon_code = $data['coupon_code'];
        $coupons->type = $data['type'];
        $coupons->amount = $data['amount'];
        $coupons->max_discount_price = $data['max_discount_price'];
        $coupons->min_tran_amount = $data['min_tran_amount'];
        $coupons->max_redeem = $data['max_redeem'];
        $coupons->category = $data['category'];
        $coupons->from_date = $data['from_date'];
        $coupons->to_date = $data['to_date'];
        $coupons->short_desc = $data['short_desc'];
        $coupons->full_desc = $data['full_desc'];
        $coupons->created_by = $data['created_by'];
        
        $coupons->save();

        return $coupons->fresh();
    }



    
    public function update($data, $id)
    {
        
        $coupons = $this->coupon->find($id);

        $coupons->coupon_title = $data['coupon_title'];
        $coupons->coupon_code = $data['coupon_code'];
        $coupons->type = $data['type'];
        $coupons->amount = $data['amount'];
        $coupons->max_discount_price = $data['max_discount_price'];
        $coupons->min_tran_amount = $data['min_tran_amount'];
        $coupons->max_redeem = $data['max_redeem'];
        $coupons->category = $data['category'];
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
        $start_date="";
        $end_date="";
        $paginate = $request->rows_number;
        $data= $this->coupon->orderBy('id','DESC');
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
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
        $coupons->delete();

        return $coupons;
    }

}