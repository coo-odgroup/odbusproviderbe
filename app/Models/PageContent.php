<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SeatOpenSeats;
use App\Models\Bus;


class PageContent extends Model
{
    use HasFactory;
    protected $table = 'page_content';
    protected $fillable = ['page_name','page_url','page_description','meta_title','meta_keyword','meta_description','extra_meta','canonical_url'];

}