<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Role;
use App\Models\UserBusOperator;
use App\Models\OdbusCharges;
use App\Models\Cancellationslabs;
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

    protected $fillable = ['name','phone','user_type'];
    
    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function odbusCharges()
    {        
        return $this->hasOne(OdbusCharges::class);        
    } 

    public function UserBusOperator()
    {        
        return $this->hasOne(UserBusOperator::class);        
    } 

    public function Cancellationslabs()
    {        
        return $this->hasOne(Cancellationslabs::class);        
    } 

}


