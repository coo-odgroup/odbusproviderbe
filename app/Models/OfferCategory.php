<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offers;
use App\Models\Amenities;


class OfferCategory extends Model
{
    use HasFactory;
    protected $table = 'offer_category';
    protected $fillable = ['category_name'];
   
    public function offers()
    {
    	return $this->hasMany(Offers::class);
    }
}
