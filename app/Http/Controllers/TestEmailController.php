<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Validator;
use App\Services\TestEmailService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


use App\Repositories\TestEmailRepository;
use Exception;
use Middleware;
use InvalidArgumentException;

class TestEmailController extends Controller
{
    
    protected $testEmailService;
    protected $testEmailRepository;

    
    public function __construct(TestEmailService $testEmailService,
                                TestEmailRepository $testEmailRepository)
    {
        $this->testEmailService = $testEmailService;
        $this->testEmailRepository = $testEmailRepository;
    }

   
    //created by Subhasis Mohanty on 11 09 2025 code cleaning

    // public function emailtest() {
    //    return  $this->testEmailService->emailtest(); 
    // }


    public function emailtest() {
       return  $this->testEmailRepository->send_email(); 
    }

     

}
