<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReviewService;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;


class ReviewController extends Controller
{
    use ApiResponser;
   
    protected $reviewService; 

    
    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;

        
    }


    public function getAll()
    {
        $reviewData = $this->reviewService->getAll();
        return $this->successResponse($reviewData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function getData(Request $request)
    {    
        $reviewData = $this->reviewService->getData($request);
        return $this->successResponse($reviewData,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function deleteData($id)
    {    
        // Log::info($request);exit;
        $reviewData = $this->reviewService->deleteData($id);
        return $this->successResponse($reviewData,"User Review Deleted",Response::HTTP_OK);
    }

    public function changeStatus($id)
    {    
        $reviewData = $this->reviewService->changeStatus($id);
        return $this->successResponse($reviewData,"User Review Status Updated",Response::HTTP_OK);
    }

}

