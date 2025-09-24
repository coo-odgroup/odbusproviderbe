<?php
namespace App\Services;
use App\Repositories\OprReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class OprReportService
{
    protected $OprReportRepository;    
   
    public function __construct(OprReportRepository $OprReportRepository)
    {
        $this->OprReportRepository = $OprReportRepository;
    }    

    // public function oprBookingReport($request)
    // {
    //     return $this->OprReportRepository->oprBookingReport($request);
    // }  

    // public function oprCancelReport($request)
    // {
    //     return $this->OprReportRepository->oprCancelReport($request);
    // }  

}