<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class ApiClientWalletRequest extends Model
{
    use HasFactory; 
    protected $table = 'client_wallet_request';
    protected $fillable = ['transaction_id','reference_id','payment_via','amount','remarks','user_id','reject_reason'];


    public function user()
    {
        return $this->belongsTo(User::class);       
    }
    
}

