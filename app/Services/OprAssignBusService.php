<?php
namespace App\Services;
use App\Repositories\OprAssignBusRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;

class OprAssignBusService
{
    protected $OprAssignBusRepository;    
   
    public function __construct(OprAssignBusRepository $OprAssignBusRepository)
    {
        $this->OprAssignBusRepository = $OprAssignBusRepository;
    }    

    public function getOprBuslist($request)
    {
        return $this->OprAssignBusRepository->getOprBuslist($request);
    }  

    public function getOprAssignBus($request)
    {
        return $this->OprAssignBusRepository->getOprAssignBus($request);
    } 

    public function OprAssignBus($request)
    {
        return $this->OprAssignBusRepository->OprAssignBus($request);
    }

      public function deleteOprAssignBus($request)
    {
        return $this->OprAssignBusRepository->deleteOprAssignBus($request);
    }  
}