<?php

namespace App\AppValidator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageStateValidator
{
    public function validate($data)
    {

        $rules = [
            'state_name' => 'required|max:50',
            'created_by' => 'required',
        ];

        return Validator::make($data, $rules);
    }

}
