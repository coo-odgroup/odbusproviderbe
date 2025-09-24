<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AssociationReportService;
use App\Repositories\AssociationReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;



class AssociationReportController extends Controller
{
    use ApiResponser;
   
    protected $AssociationReportService;
    protected $AssociationReportRepository;
    
    public function __construct(AssociationReportService $AssociationReportService,
                                AssociationReportRepository $AssociationReportRepository )
    {
        $this->AssociationReportService = $AssociationReportService;       
        $this->AssociationReportRepository = $AssociationReportRepository;       
    }


     public function assocBookingReport(Request $request)
     {
          //return $this->AssociationReportService->assocBookingReport($request);
          return $this->AssociationReportRepository->assocBookingReport($request);
            
	 }  

	 public function assocCancelReport(Request $request)
     {
          //return $this->AssociationReportService->assocCancelReport($request);
           return $this->AssociationReportRepository->assocCancelReport($request);
            
	 }  

}