<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingArchive extends Model
{
    protected $guarded = [];

    protected $table;
    protected $primaryKey = 'booking_id';
    public $timestamps = false; // remove if timestamps exist

    /*
    |--------------------------------------------------------------------------
    | Dynamic Year Table Setter
    |--------------------------------------------------------------------------
    */
    public function setYear($year)
    {
        $this->setTable($year . '_booking');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Booking → Booking Details
    public function details()
    {
        return $this->hasMany(BookingDetailArchive::class, 'booking_id', 'booking_id');
    }

    // Booking → Payment
    public function payment()
    {
        return $this->hasOne(CustomerPaymentArchive::class, 'booking_id', 'booking_id');
    }

    // Booking → User
    public function user()
    {
        return $this->belongsTo(Users::class, 'users_id', 'id');
    }

    // Booking → Bus
    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'id');
    }

    // Booking → Source Location
    public function source()
    {
        return $this->belongsTo(Location::class, 'source_id', 'id');
    }

    // Booking → Destination Location
    public function destination()
    {
        return $this->belongsTo(Location::class, 'destination_id', 'id');
    }

    // Booking → Client Wallet (Credit Only)
    public function clientWallet()
    {
        return $this->hasMany(ApiClientWallet::class, 'booking_id', 'booking_id')
                    ->where('transaction_type', 'c');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Optional - For Seat Count & Numbers)
    |--------------------------------------------------------------------------
    */

    public function getTotalSeatsAttribute()
    {
        return $this->details->count();
    }

    public function getSeatNumbersAttribute()
    {
        return $this->details
            ->pluck('seat.seatText')
            ->filter()
            ->implode(',');
    }
}