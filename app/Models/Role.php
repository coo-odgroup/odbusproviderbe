<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\User;
use App\Models\PermissionToRole;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{    
    protected $table = 'role';
    protected $fillable = ['name', 'created_at', 'created_by'];       

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function PermissionToRole()
    {
        return $this->hasMany(PermissionToRole::class);
    }
}
