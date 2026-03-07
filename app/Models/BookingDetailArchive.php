<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetailArchive extends Model
{
    protected $guarded = [];

    protected $table = 'booking_detail'; // base table name
    protected $primaryKey = 'id';
    public $timestamps = false; // remove if timestamps exist

    protected static $year;

    /*
    |--------------------------------------------------------------------------
    | Dynamic Year Setter
    |--------------------------------------------------------------------------
    */
    public static function setYear($year)
    {
        static::$year = $year;
    }

    public function getTable()
    {
        if (static::$year) {
            return static::$year . '_' . $this->table;
        }

        return parent::getTable();
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Booking Detail → Booking
    public function booking()
    {
        return $this->belongsTo(BookingArchive::class, 'booking_id', 'booking_id');
    }

    // Booking Detail → Bus Seat
    public function busSeat()
    {
        return $this->belongsTo(BusSeats::class, 'bus_seats_id', 'id');
    }

    // Booking Detail → Seat (via BusSeat)
    public function seat()
    {
        return $this->hasOneThrough(
            Seats::class,     // Final Model
            BusSeats::class,  // Intermediate Model
            'id',             // Foreign key on BusSeats table...
            'id',             // Foreign key on Seats table...
            'bus_seats_id',   // Local key on BookingDetailArchive
            'seats_id'        // Local key on BusSeats
        )->select([
            'seats.id',
            'seats.berthType',
            'seats.seatText',
        ]);
    }
}