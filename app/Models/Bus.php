<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusSeats;
use App\Models\BusAmenities;
use App\Models\BusSafety;

use App\Models\BusSeatLayout;
use App\Models\bus_seats;

// use App\Models\Amenities;
use App\Models\CityClosing;
use App\Models\BusContacts;
use App\Models\BusStoppage;
use App\Models\Review;
use App\Models\BusSchedule;
use App\Models\BusOperator;
use App\Models\BusCancelled;
use App\Models\Cancellationslabs;
use App\Models\TicketPrice;
use App\Models\BusStoppageTiming;
use App\Models\BookingSeized;






//bus_seats  bus_amenities city_closing bus_contacts bus_stoppage bus_stoppage_timing
class Bus extends Model
{
    use HasFactory; 
    protected $table = 'bus';
    protected $fillable = [ 
        'bus_operator_id','user_id', 'name','via','bus_number','bus_description','bus_type_id','bus_sitting_id','amenities_id','cancellationslabs_id','bus_seat_layout_id','running_cycle','popularity','admin_notes','has_return_bus', 'return_bus_id','cancelation_points','created_by',
    ];
    public function busAmenities()
    {
        return $this->hasMany(BusAmenities::class);        
    } 

    public function ticketPrice()
    {
        return $this->hasMany(TicketPrice::class)->where('status','!=',2);        
    } 

    public function busSafety()
    {
        return $this->hasMany(BusSafety::class);        
    } 
    public function review()
    {        
        return $this->hasMany(Review::class);        
    } 
    public function busSchedule()
    {        
        return $this->hasMany(BusSchedule::class);        
    } 
    public function busCancelled()
    {        
        return $this->hasOne(BusCancelled::class);        
    } 
    public function busstoppage()
    {        
        return $this->hasMany(BusStoppage::class);        
    }  
    public function busSeats()
    {        
        return $this->hasMany(BusSeats::class);        
    }     
    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }
    public function specialFare()
    {
        return $this->belongsToMany(SpecialFare::class);       
    }

    public function festivalFare()
    {
        return $this->belongsToMany(FestivalFare::class);       
    }
    public function busContacts()
        {
            return $this->hasMany(BusContacts::class);       
        }

    public function BusSitting()
    {
        return $this->belongsTo(BusSitting::class);
    }
    public function BusType()
    {
        return $this->belongsTo(BusType::class);
    }

    public function busSeatLayout()
    {
        return $this->belongsTo(BusSeatLayout::class);
    }

    public function bus_seats()
    {
        return $this->hasMany(BusSeats::class);
    }

    public function ownerfare()
    {
        return $this->belongsToMany(OwnerFare::class);
    } 
    public function bookingseized()
    {
        return $this->hasMany(BookingSeized::class);
    }
    public function cancellationslabs()
    {        
        return $this->belongsTo(Cancellationslabs::class);        
    } 
    public function busStoppageTimimg()
    {        
        return $this->hasMany(BusStoppageTiming::class);        
    }

}
