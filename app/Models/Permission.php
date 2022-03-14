<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
//use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{    
    protected $table = 'permission';
    protected $fillable = ['name', 'created_at', 'created_by'];       

    // public function user()
    // {
    //     return $this->hasOne(User::class);
    // }
}
