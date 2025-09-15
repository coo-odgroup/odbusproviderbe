<?php

namespace App\Services;
use App\Repositories\ExtraSeatBlockRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;

class ExtraSeatBlockService
{
    protected $extraSeatBlockRepository;

    public function __construct(ExtraSeatBlockRepository $extraSeatBlockRepository)
    {
        $this->extraSeatBlockRepository = $extraSeatBlockRepository;
    }

   
    // public function deleteExtraSeatBlock($request)
    // {
    //     try {
    //         $seatblock = $this->extraSeatBlockRepository->deleteExtraSeatBlock($request);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $seatblock;
    // }    
   

    // public function addExtraSeatBlock($request)
    // {
    //     return $this->extraSeatBlockRepository->addExtraSeatBlock($request);
    // } 

    // public function addExtraSeatBlockByOperator($request)
    // {
    //     return $this->extraSeatBlockRepository->addExtraSeatBlockByOperator($request);
    // }
   
    // public function extraSeatBlockData($request)
    // {
    //     return $this->extraSeatBlockRepository->extraSeatBlockData($request);
    // }

}