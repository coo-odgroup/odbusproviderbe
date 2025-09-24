<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\OprReportService;
use Illuminate\Support\Facades\Validator;
use App\Repositories\OprReportRepository;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OprReportController extends Controller
{
    use ApiResponser;
   
    protected $OprReportService;
    protected $OprReportRepository;    
    
    
    public function __construct(OprReportService $OprReportService,
                                OprReportRepository $OprReportRepository )
    {
        $this->OprReportService = $OprReportService;          
        $this->OprReportRepository = $OprReportRepository;
    }        
    


     public function oprBookingReport(Request $request)
     {
          //return $this->OprReportService->oprBookingReport($request);
           return $this->OprReportRepository->oprBookingReport($request);
            
	 }  

	 public function oprCancelReport(Request $request)
     {
          //return $this->OprReportService->oprCancelReport($request);
          return $this->OprReportRepository->oprCancelReport($request);
            
	 }  

}