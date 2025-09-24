<?php
namespace App\Services;
use App\Repositories\AssociationAssignAgentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AssociationAssignAgentService
{
    protected $AssociationAssignAgentRepository;

    
   
    public function __construct(AssociationAssignAgentRepository $AssociationAssignAgentRepository)
    {
        $this->AssociationAssignAgentRepository = $AssociationAssignAgentRepository;
    }
    
    

    // public function getAssocBuslist($request)
    // {
    //     return $this->AssociationAssignAgentRepository->getAssocBuslist($request);
    // }  

    // public function getassocAssignAgent($request)
    // {
    //     return $this->AssociationAssignAgentRepository->getassocAssignAgent($request);
    // } 

    // public function assocAssignAgent($request)
    // {
    //     return $this->AssociationAssignAgentRepository->assocAssignAgent($request);
    // }

    //   public function deleteassocAssignAgent($request)
    // {
    //     return $this->AssociationAssignAgentRepository->deleteassocAssignAgent($request);
    // } 

  

}