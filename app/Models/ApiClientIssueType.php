<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiClientIssue;


class ApiClientIssueType extends Model
{
    use HasFactory; 
    protected $table = 'apiclientissuetype';
    protected $fillable = ['name','status','created_at','updated_at','created_by'];

    public function apiClientIssue()
    {
        return $this->hasMany(ApiClientIssue::class);        
    } 
}
