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
use App\Models\ApiClientWallet;
use App\Models\Location;
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

    public function ClientWallet()
    {
        return $this->hasMany(ApiClientWallet::class)->where('transaction_type', '=', 'c');
    }

    public function CustomerPayment()
    {
        return $this->hasOne(CustomerPayment::class, 'booking_id', 'id');
    }

    public function CustomerPaymentData()
    {
        return $this->hasOne(CustomerPayment::class);
    }

    public function UserBooking()
    {
        return $this->hasOne(UserBooking::class);
    }

<<<<<<< HEAD
    // public function bookingDetails()
    // {
    //     return $this->hasMany(BookingDetail::class, 'booking_id', 'id');
    // }

    public function booking_details()
    {
        return $this->hasMany(BookingDetail::class, 'booking_id')->with('seat:id,berthType,seatText');
    }

    public function usersData()
    {
        return $this->belongsTo(Users::class, 'users_id','id');
    }



=======
    public function Source()
    {
        return $this->belongsTo(Location::class, 'source_id');
    }

    public function Destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }
>>>>>>> 49d1c5472d5caae73b23306acfcb1f82dea1fcbb
}
