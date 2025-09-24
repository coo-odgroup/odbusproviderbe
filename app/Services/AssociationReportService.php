<?php
namespace App\Services;
use App\Repositories\AssociationReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssociationReportService
{
    protected $AssociationReportRepository;

    
   
    public function __construct(AssociationReportRepository $AssociationReportRepository)
    {
        $this->AssociationReportRepository = $AssociationReportRepository;
    }
    
    

    // public function assocBookingReport($request)
    // {
    //     return $this->AssociationReportRepository->assocBookingReport($request);
    // }  


    // public function assocCancelReport($request)
    // {
    //     return $this->AssociationReportRepository->assocCancelReport($request);
    // }  

   

  

}