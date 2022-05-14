<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
use App\Models\User;
use App\Models\Bus;
use App\Models\BookingDetail;
use App\Models\CustomerPayment;
use App\Models\UserBooking;


class Booking extends Model
{
    use HasFactory;
    protected $table = 'booking';
    protected $fillable = ['transaction_id','pnr','customer_id','user_id','bus_id','source_id',
                            'destination_id','j_day','journey_dt','boarding_id','dropping_id',
                            'boarding_time','dropping_time','bus_info','customer_info',
                            'total_fare','ownr_fare','is_coupon','coupon_code','coupon_discount',
                            'discounted_fare','origin','app_type','typ_id','created_by'];

      public function Users()
      {
            return $this->belongsTo(Users::class);
      } 

      public function User()
      {
            return $this->belongsTo(User::class);
      }

      public function Bus()
      {
            return $this->belongsTo(Bus::class);
      } 

      public function BookingDetail()
      {
            return $this->hasMany(BookingDetail::class);
      }
      
      public function CustomerPayment()
      {
            return $this->hasOne(CustomerPayment::class);
      }  

      public function UserBooking()
      {
            return $this->hasOne(UserBooking::class);
      }

}
