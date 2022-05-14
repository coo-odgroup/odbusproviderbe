<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\User;
use App\Models\Bus;
class Users extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [];

     // public function User()
     //  {
     //        return $this->belongsTo(User::class);
     //  }

      public function Bus()
      {
            return $this->belongsTo(Bus::class);
      }

      public function booking()
      {
            return $this->hasMany(Booking::class);   
      } 

}
