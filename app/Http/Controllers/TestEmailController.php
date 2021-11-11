<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Validator;
use App\Services\TestEmailService;
use Exception;
use Middleware;
use InvalidArgumentException;

class TestEmailController extends Controller
{
    
    protected $testEmailService;

    
    public function __construct(TestEmailService $testEmailService)
    {
        $this->testEmailService = $testEmailService;
    }


    public function emailtest() {
       return  $this->testEmailService->emailtest(); 
    }

     

}
