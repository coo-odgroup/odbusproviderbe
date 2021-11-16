<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\BusOperator;
use Illuminate\Database\Eloquent\Model;

class UserBusOperator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_bus_operator';    

    protected $fillable = ['user_id','bus_operator_id'];
    public function BusOperator()
    {        
        return $this->belongsTo(BusOperator::class);        
    } 
}


