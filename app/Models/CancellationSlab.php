<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CancellationSlabInfo;
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

}
