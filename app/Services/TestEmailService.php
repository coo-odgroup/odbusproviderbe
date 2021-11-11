<?php

namespace App\Services;

use App\Repositories\TestEmailRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TestEmailService
{
    protected $testEmailRepository;
    public function __construct(TestEmailRepository $testEmailRepository)
    {
        $this->testEmailRepository = $testEmailRepository;
    }
    
    public function emailtest()
    {      
            return $this->testEmailRepository->send_email();
    }
}