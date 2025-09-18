<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//se App\Services\SeatBlockReportService;
use App\Repositories\SeatBlockReportRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SeatBlockReportController extends Controller
{
    use ApiResponser;
   
   // protected $seatblockreportService; 
    protected $seatblockreportRepository;   
    
    public function __construct(//SeatBlockReportService $seatblockreportService, 
                                SeatBlockReportRepository $seatblockreportRepository
)
    {
        //$this->seatblockreportService = $seatblockreportService;
        $this->seatblockreportRepository = $seatblockreportRepository;
        
    }


    // public function getAllseatblock()
    // {
    //     $seatblock = $this->seatblockreportService->getAll();
    //     return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // } 

     public function getAllseatblock()
    {
        $seatblock = $this->seatblockreportRepository->getAll();
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    } 
    // public function getData(Request $request)
    // {
    //     $seatblock = $this->seatblockreportService->getData($request);
    //     return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
    public function getData(Request $request)
    {
        $seatblock = $this->seatblockreportRepository->getData($request);
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}