<?php

namespace App\Services;


use App\Repositories\DashboardRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class DashboardService
{
    protected $dashboardRepository;

    
   
    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }  
    
    
    public function getAll($request)
    {
        return $this->dashboardRepository->getAll($request);
    } 

    public function getAllAgentData($request)
    {
        return $this->dashboardRepository->getAllAgentData($request);
    } 

    public function getRoute($request)
    {
        return $this->dashboardRepository->getRoute($request);
    }  

    public function getOperator()
    {
        return $this->dashboardRepository->getOperator();
    }

    public function getticketstatics()
    {
        return $this->dashboardRepository->getticketstatics();
    }
    public function getbookingbydevice()
    {
        return $this->dashboardRepository->getbookingbydevice();
    }
    public function getpnrstatics($request)
    {
        return $this->dashboardRepository->getpnrstatics($request);
    }

}