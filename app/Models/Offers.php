<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OfferCategory;
use App\Models\Amenities;


class Offers extends Model
{
    use HasFactory;
    protected $table = 'offers';
    protected $fillable = ['offer_category_id','offer_image'];
   
    public function offercategory()
    {
    	return $this->belongsTo(OfferCategory::class);
    }
}
