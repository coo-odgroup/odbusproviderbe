<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';
    // public $timestamps = false;
    protected $fillable = [
        'occassion', 'url', 'slider_img','alt_tag','start_date','end_date'
    ];
}
