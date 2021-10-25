<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;
use App\Models\BusOperator;


class BusGallery extends Model
{
    use HasFactory; 
    protected $table = 'bus_gallery';
    protected $fillable = ['bus_id', 'image','alt_tag','created_by'];
    
    public function bus()
    {
    	return $this->belongsTo(Bus::class);
    }
    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }
    
}
