<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class CustomerPayment extends Model
{
    use HasFactory;
    protected $table = 'customer_payment';
    protected $fillable = ['name','amount','payment_id','razorpay_id','payment_done','refund_mode'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
