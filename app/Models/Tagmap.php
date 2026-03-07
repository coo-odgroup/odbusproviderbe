<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagmap extends Model
{
    protected $table = "blog_tag_map";
    use HasFactory;

    protected $guarded;
}
