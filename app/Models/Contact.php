<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusOperator;


class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = ['name','email','phone','service','message','created_at','updated_at'];


	public function BusOperator()
	{        
		return $this->belongsTo(BusOperator::class);        
	} 

}
    

