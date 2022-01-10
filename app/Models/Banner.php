<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusOperator;
use App\Models\User;

class Banner extends Model
{
    use HasFactory;
    protected $table = 'banner';
    protected $fillable = [
       'occassion','category','url', 'banner_img','alt_tag','start_date','start_time','end_date','end_time','created_by'
    ];


     public function busOperator()
    {
        return $this->belongsTo(BusOperator::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
