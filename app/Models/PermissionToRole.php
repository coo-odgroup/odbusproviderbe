<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\role;
use App\Models\Permission;


class PermissionToRole extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';    

    protected $fillable = ['permission_id','role_id','created_at','updated_at','created_by'];

    public function Role()
    {
        return $this->belongsTo(Role::class);
    }

    public function Permission()
    {
        return $this->belongsTo(Permission::class);
    }
}