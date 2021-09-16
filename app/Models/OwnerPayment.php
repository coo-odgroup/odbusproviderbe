<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusOperator;


//bus_seats  bus_amenities city_closing bus_contacts bus_stoppage bus_stoppage_timing
class OwnerPayment extends Model
{
    use HasFactory; 
    protected $table = 'ownerpayment';
    protected $fillable = ['bus_operator_id','date','amount','remark','created_by'];
    

    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }
}
