<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityContent extends Model
{
    protected $table= 'mst_city_content';
    use HasFactory;

    protected $guarded = [];
}
