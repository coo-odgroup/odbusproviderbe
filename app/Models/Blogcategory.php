<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blogcategory extends Model
{
    use SoftDeletes;

    protected $table = "blog_categories";
    use HasFactory;

    protected $guarded;
}
