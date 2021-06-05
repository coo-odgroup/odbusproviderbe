<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CancellationSlab extends Model
{
    use HasFactory;
    protected $table = 'cancellationslabs';
    protected $fillable = [ 
        'rule_name', 'duration', 'deduction', 'status'
    ];

}
