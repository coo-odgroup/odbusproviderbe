<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';    
    //protected $fillable = ['name', 'email','phone','password','created_by'];

    protected $fillable = ['name', 'email'];
   // protected $hidden = ['password', 'remember_token' ];
    

    public function role()
    {
        return $this->hasOne(Role::class);
    }
}
