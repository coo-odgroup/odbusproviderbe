<?php

namespace App\Services;

use App\Repositories\ApiClientIssueRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiClientIssueService
{
    protected $ApiClientIssueRepository;
    public function __construct(ApiClientIssueRepository $ApiClientIssueRepository)
    {
        $this->ApiClientIssueRepository = $ApiClientIssueRepository;
    }
    

    public function apiclientissuetype()
    {
        return $this->ApiClientIssueRepository->apiclientissuetype();
    }
    public function apiclientissuesubtype($request)
    {
        return $this->ApiClientIssueRepository->apiclientissuesubtype($request);
    }

    public function apiclientissuedata($request)
    {
        return $this->ApiClientIssueRepository->apiclientissuedata($request);
    }
    public function addapiclientissue($request)
    {
        return $this->ApiClientIssueRepository->addapiclientissue($request);
    }

    
    // public function changeStatus($id)
    // {
    //     try {
    //         $busType = $this->ApiClientIssueRepository->changeStatus($id);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
    //     }
    //     return $busType;

    // }

}