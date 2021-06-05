<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupon';
    protected $fillable = [  'coupon_title','coupon_code','type','amount', 
                            'max_discount_price','min_tran_amount','max_redeem',
                            'max_use_limit','category','from_date','to_date','short_desc','full_desc',
                            'created_by'];
 

}
