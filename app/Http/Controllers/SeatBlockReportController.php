<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatBlockReportService;

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
   
    protected $seatblockreportService;    
    
    public function __construct(SeatBlockReportService $seatblockreportService)
    {
        $this->seatblockreportService = $seatblockreportService;
        
    }


    public function getAllseatblock()
    {
        $seatblock = $this->seatblockreportService->getAll();
        return $this->successResponse($seatblock,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

}