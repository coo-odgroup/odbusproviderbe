<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiClientIssue;

class ApiClientIssueSubType extends Model
{
    use HasFactory; 
    protected $table = 'apiclientissuesubtype';
    protected $fillable = ['name','heading','status','created_by','created_at','updated_at'];
    
    public function apiClientIssue()
    {
        return $this->hasMany(ApiClientIssue::class);        
    } 

   

}
