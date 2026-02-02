<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDetailArchive extends Model
{
    protected $guarded = [];
    protected $table = 'booking_detail'; // base name only

    protected static $year;

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

    /* ================= Relations ================= */

    public function Bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function BusSeats()
    {
        return $this->belongsTo(BusSeats::class, 'bus_seats_id');
    }

    public function busSeat()
    {
        return $this->belongsTo(BusSeats::class, 'bus_seats_id');
    }

    public function seat()
    {
        return $this->hasOneThrough(
            Seats::class,
            BusSeats::class,
            'id',
            'id',
            'bus_seats_id',
            'seats_id'
        )->select([
            'seats.id',
            'seats.berthType',
            'seats.seatText',
        ]);
    }

    public function booking()
    {
        return $this->belongsTo(BookingArchive::class, 'booking_id');
    }
}
