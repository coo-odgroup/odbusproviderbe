<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

class AgentSlider extends Model
{
    use HasFactory;

    protected $table = 'agent_slider';

    protected $fillable = [
        'url',
        'image_path',
        'alt_tag',
        'slider_description',
        'file_name',
        'default_slider',
        'sequence',
        'start_date',
        'end_date',
        'status',
        'created_at',
        'updated_at',
        'created_by',
    ];

}
