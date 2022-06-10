<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApiClientIssueType;
use App\Models\ApiClientIssueSubType;
use App\Models\Bus;


class ApiClientIssue extends Model
{
    use HasFactory; 
    protected $table = 'apiclientissue';
    protected $fillable = ['user_id','apiclientissuetype_id','apiclientissuesubtype_id','bus_id','bus_operator_id','source_id','destination_id','reference_id','created_at','created_by','updated_at'];
   
    public function apiclientissuetype()
    {
        return $this->belongsTo(ApiClientIssueType::class);        
    }

	public function apiclientissuesubtype()
    {
        return $this->belongsTo(ApiClientIssueSubType::class);        
    } 
    public function bus()
    {
        return $this->belongsTo(Bus::class);        
    } 

   

}
