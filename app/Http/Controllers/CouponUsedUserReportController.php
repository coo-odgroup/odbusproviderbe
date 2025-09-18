<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\CouponUsedUserReportService;
use App\Repositories\CouponUsedUserReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CouponUsedUserReportController extends Controller
{
    use ApiResponser;
   
    //protected $couponuseduserreportService; 
    protected $couponuseduserreportRepository;   
    
    public function __construct(//CouponUsedUserReportService $couponuseduserreportService                        
                                CoupenUsedUserReportRepository $couponuseduserreportRepository)
    {
        //$this->couponuseduserreportService = $couponuseduserreportService;  
        $this->couponuseduserreportRepository = $couponuseduserreportRepository;      
    }

    // public function getData(Request $request)
    // {
    //     $couponused = $this->couponuseduserreportService->getData($request);
    //     return $this->successResponse($couponused,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    public function getData(Request $request)
    {
        $couponused = $this->couponuseduserreportRepository->getData($request);
        return $this->successResponse($couponused,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}