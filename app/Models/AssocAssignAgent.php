<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class AssocAssignAgent extends Model
{
    use HasFactory;

    protected $table = 'assign_agent';    

    protected $fillable = ['user_id','agent_id','created_at','updated_at','created_by'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Users()
    {
        return $this->belongsTo(Users::class);
    }
}