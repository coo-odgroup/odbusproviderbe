<?php
namespace App\Services;
use App\Repositories\AssociationAssignBusRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssociationAssignBusService
{
    protected $AssociationAssignBusRepository;

    
   
    public function __construct(AssociationAssignBusRepository $AssociationAssignBusRepository)
    {
        $this->AssociationAssignBusRepository = $AssociationAssignBusRepository;
    }
    
    

    // public function getAssocBuslist($request)
    // {
    //     return $this->AssociationAssignBusRepository->getAssocBuslist($request);
    // }  

    // public function getassocAssignBus($request)
    // {
    //     return $this->AssociationAssignBusRepository->getassocAssignBus($request);
    // } 

    // public function assocAssignBus($request)
    // {
    //     return $this->AssociationAssignBusRepository->assocAssignBus($request);
    // }

    //   public function deleteassocAssignBus($request)
    // {
    //     return $this->AssociationAssignBusRepository->deleteassocAssignBus($request);
    // } 

  

}