<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\busSafety;

class Safety extends Model
{
    use HasFactory;
    protected $table = 'safety';
    // public $timestamps = false;
    protected $fillable = [
        'name'
    ];
    public function busSafety()
    {
       return $this->hasMany(BusSafety::class);
        
    } 
    

}
