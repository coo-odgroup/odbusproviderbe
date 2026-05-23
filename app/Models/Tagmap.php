<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagmap extends Model
{
    use SoftDeletes;
    protected $table = "blog_tag_maps";
    use HasFactory;

    protected $guarded;
}
