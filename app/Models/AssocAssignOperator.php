<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BusOperator;

class AssocAssignOperator extends Model
{
    use HasFactory;

    protected $table = 'assign_operator';    

    protected $fillable = ['user_id','operator_id','created_at','updated_at','created_by'];
   

    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

}


