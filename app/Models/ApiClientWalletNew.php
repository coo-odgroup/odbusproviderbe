<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;


class ApiClientWalletNew extends Model
{
    use HasFactory; 
    protected $table = 'client_wallet_new';
    protected $fillable = ['transaction_id','reference_id','payment_via','amount','remarks','user_id','reject_reason'];


    public function user()
    {
        return $this->belongsTo(User::class);       
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class);       
    }
    
}
