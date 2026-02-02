<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingArchive extends Model
{
    protected $guarded = [];
    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // $year = $year ?? date('Y');
        // $this->table = '2022' . '_booking';
    }

    public function setYear($year)
    {
        $this->setTable($year . '_booking');
        return $this;
    }

    public function BookingDetail()
    {
        return $this->hasMany(BookingDetailArchive::class, 'booking_id');
    }

    public function CustomerPayment()
    {
        return $this->hasOne(CustomerPaymentArchive::class, 'booking_id');
    }


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

    // public function BookingDetail()
    // {
    //     return $this->hasMany(BookingDetail::class);
    // }

    public function ClientWallet()
    {
        return $this->hasMany(ApiClientWallet::class)->where('transaction_type', '=', 'c');
    }

    // public function CustomerPayment()
    // {
    //     return $this->hasOne(CustomerPayment::class, 'booking_id', 'id');
    // }

    public function CustomerPaymentData()
    {
        return $this->hasOne(CustomerPayment::class);
    }

    public function UserBooking()
    {
        return $this->hasOne(UserBooking::class);
    }

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



    public function Source()
    {
        return $this->belongsTo(Location::class, 'source_id');
    }

    public function Destination()
    {
        return $this->belongsTo(Location::class, 'destination_id');
    }
}

