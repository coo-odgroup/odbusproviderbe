<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    protected $table = 'social_link';
    protected $fillable = [
        'facebook_link', 'twitter_link', 'instagram_link', 'googleplus_link', 'linkedin_link'
    ];
}
