<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CancellationSlabInfo;
use App\Models\Bus;
class Cancellationslabs extends Model
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
    	return $this->hasMany(Bus::class);
    }

}
