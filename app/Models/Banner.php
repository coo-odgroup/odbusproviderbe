<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner';
    protected $fillable = [
       'occassion','category','url', 'banner_img','alt_tag','start_date','start_time','end_date','end_time','created_by'
    ];
}
