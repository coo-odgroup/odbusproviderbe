<?php

namespace App\Repositories;

use App\Models\Coupon;

class CouponRepository
{
    
    protected $coupon;

    
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    
    public function getAll()
    {
        return $this->coupon->get();
    }

    
    public function getById($id)
    {
        return $this->coupon ->where('id', $id)->get();
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

    
    public function delete($id)
    {
        
        $coupons = $this->coupon->find($id);
        $coupons->delete();

        return $coupons;
    }

}