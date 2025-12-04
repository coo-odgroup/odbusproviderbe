<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhonePayToken extends Model
{
    use HasFactory; 
    protected $table = 'phonepay_token';
    protected $fillable = ['access_token','encrypted_access_token','expires_in','issued_at','expires_at','session_expires_at','token_type','created_at','updated_at'];
}