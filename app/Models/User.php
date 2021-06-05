<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserBankDetails;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user';
    // public $timestamps = false;
    protected $fillable = [
        'user_pin', 'first_name', 'middle_name','last_name','thumbnail','email','location','org_name','address','phone','alternate_phone','alternate_email','password', 
        'user_role','rand_key','created_by',
    ];
    public function userBankDetails()
    {
        return $this->hasMany(UserBankDetails::class);
        
    } 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
         'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
     protected $casts = [
        'email_verified_at' => 'datetime',
     ];
     public function buses()
    {
        return $this->hasMany(Bus::class);
        
    } 
}
