<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUserCommission extends Model
{
    use HasFactory; 
    protected $table = 'client_commission_slab';
    protected $fillable = ['user_id','starting_fare','upto_fare','commision','created_by','status'];
    
}
