<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'role';
    // public $timestamps = false;
    protected $fillable = [];

    
    

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
