<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\OwnerPaymentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class OwnerPaymentService
{
    
    protected $ownerPaymentRepository;

    
    public function __construct(OwnerPaymentRepository $ownerPaymentRepository)
    {
        $this->ownerPaymentRepository = $ownerPaymentRepository;
    }

   
    
    public function getAll()
    {
        return $this->ownerPaymentRepository->getAll();
    }
   
    public function dataTable($request)
    {
        
        return $this->ownerPaymentRepository->getDatatable($request);
    }
        
    public function savePostData($data)
    {
        try {
            $post = $this->ownerPaymentRepository->save($data);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
   

}