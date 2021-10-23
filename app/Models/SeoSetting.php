<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusOperator;




class SeoSetting extends Model
{
    use HasFactory;
    protected $table = 'seo_setting';
    protected $fillable = ['page_url','meta_title','meta_keyword','meta_description','extra_meta','canonical_url'];



	public function BusOperator()
	{        
		return $this->belongsTo(BusOperator::class);        
	} 
}