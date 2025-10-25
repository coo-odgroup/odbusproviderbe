<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\AssociationReportService;
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
    
    
    public function __construct(AssociationReportService $AssociationReportService )
    {
        $this->AssociationReportService = $AssociationReportService;              
    }


     public function assocBookingReport(Request $request)
     {
          return $this->AssociationReportService->assocBookingReport($request);
            
	 }  

	 public function assocCancelReport(Request $request)
     {
          return $this->AssociationReportService->assocCancelReport($request);
            
	 }  

}