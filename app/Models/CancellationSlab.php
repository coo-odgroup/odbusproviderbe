<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CancellationSlabInfo;
use App\Models\Bus;
use App\Models\User;

class CancellationSlab extends Model
{
    use HasFactory;
    protected $table = 'cancellationslabs';
    protected $fillable = [ 
        'rule_name', 'status'
    ];

    public function SlabInfo()
    {
        return $this->hasMany(CancellationSlabInfo::class);        
    }
    public function Bus()
    {
    	return $this->hasOne(Bus::class);
    }
    public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
        
    }

    public function User()
	{        
		return $this->belongsTo(User::class);        
	} 

}
