<?php

namespace App\Services;


use App\Repositories\CouponUsedUserReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class CouponUsedUserReportService
{
    protected $couponuseduserreportRepository;

    
   
    public function __construct(CouponUsedUserReportRepository $couponuseduserreportRepository)
    {
        $this->couponuseduserreportRepository = $couponuseduserreportRepository;
    }  
    
    
    // public function getData($request)
    // {
    //     return $this->couponuseduserreportRepository->getData($request);
    // }

}