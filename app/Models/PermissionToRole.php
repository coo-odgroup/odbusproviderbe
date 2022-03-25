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

    protected $fillable = ['role_id','menu','submenu','add_status','edit_status','view_status','delete_status','created_at','updated_at','created_by'];

    public function Role()
    {
        return $this->belongsTo(Role::class);
    }

    public function Permission()
    {
        return $this->belongsTo(Permission::class);
    }
}