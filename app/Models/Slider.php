<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';
    protected $fillable = [
       'slider', 'occassion','category','url', 'slider_img','alt_tag','start_date','end_date'
    ];
}
