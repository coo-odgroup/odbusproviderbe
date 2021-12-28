<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Safety;


class BusSafety extends Model
{
    use HasFactory;
    protected $table = 'bus_safety';
    // public $timestamps = false;
    // protected $fillable = [
    //     'name'
    // ];
    //protected $appends = ['Disabled'];
    public function safety()
    {
       return $this->belongsTo(Safety::class);
        
    } 

}
