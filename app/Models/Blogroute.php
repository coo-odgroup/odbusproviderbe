<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogroute extends Model
{
    protected $table = "blog_routes";
    use HasFactory;


    protected $guarded = [];
}
