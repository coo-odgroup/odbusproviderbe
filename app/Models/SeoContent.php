<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoContent extends Model
{
    use HasFactory;

    protected $table = 'mst_seo_content';
    protected $guarded = [];
}
