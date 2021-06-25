<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CancellationSlabInfo extends Model
{
    use HasFactory;
    protected $table = 'cancellationslabs_info';
    protected $fillable = [ 
        'slabs','created_by'
    ];

}
