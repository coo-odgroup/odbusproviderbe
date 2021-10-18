<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;
use App\Models\BusOperator;

class CouponOperator extends Model
{
    use HasFactory;
    protected $table = 'coupon_operator';
    protected $fillable = ['coupon_id','operator_id','created_by'];
    public function coupon()
    {
    	return $this->belongsTo(Coupon::class);
    }
    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }
}
