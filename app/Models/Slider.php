<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Coupon;


class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';
    protected $fillable = [
       'bus_operator_id','occassion','category','url', 'slider_img','alt_tag','start_date','start_time','end_date','end_time','created_by'
    ];

    public function coupon()
	{        
		return $this->belongsTo(Coupon::class);        
	}
}
