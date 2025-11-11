<?php

namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationValidator
{
    public function validate($data)
    {

        $rules = [
            'name' => 'required|max:50',
            'state_id' => 'required',
            'synonym' => '',
            'created_by' => 'required',
        ];

        return Validator::make($data, $rules);
    }

}
