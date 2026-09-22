<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiVendor extends Model
{
    use HasFactory;
    protected $table = 'api_vendors';
    protected $fillable = ['id', 'vendor_code', 'company_name', 'status', 'contact_name', 'contact_email', 'contact_phone', 'rate_limit_per_minute', 'risk_score', 'activated_at', 'suspended_at', 'has_gst', 'created_at', 'created_by','updated_at','updated_by', ];
}
