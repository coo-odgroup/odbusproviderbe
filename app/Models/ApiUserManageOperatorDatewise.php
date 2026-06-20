<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUserManageOperatorDatewise extends Model
{
    protected $table = 'manageclientoperator_datewise';

    protected $fillable = [
        'user_id',
        'bus_operator_id',
        'journey_date',
        'reason',
        'created_by',
        'status'
    ];

    public $timestamps = true;
}