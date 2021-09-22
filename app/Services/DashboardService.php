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
    
    
    public function getAll()
    {
        return $this->dashboardRepository->getAll();
    }  
    public function getRoute()
    {
        return $this->dashboardRepository->getRoute();
    }  
    public function getOperator()
    {
        return $this->dashboardRepository->getOperator();
    }

}