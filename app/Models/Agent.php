<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory; 
    protected $table = 'user';
    protected $fillable = ['name','email','phone','password','user_type','otp','location','adhar_no','pancard_no','organization_name','address','street','landmark','city','pincode','name_on_bank_account','bank_name','ifsc_code','bank_account_no','branch_name','upi_id','created_by','status'];
    
}
