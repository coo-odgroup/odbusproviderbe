<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ReviewRepository;
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

   protected $reviewRepository;


    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;


    }


    public function getAll()
    {
        $reviewData = $this->reviewRepository->getAll();
        return $this->successResponse($reviewData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function getData(Request $request)
    {
        $reviewData = $this->reviewRepository->getData($request);
        return $this->successResponse($reviewData, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }
    public function deleteData($id)
    {
        // Log::info($request);exit;
        $reviewData = $this->reviewRepository->getData($id);
        return $this->successResponse($reviewData, "User Review Deleted", Response::HTTP_OK);
    }

    public function changeStatus($id)
    {
        $reviewData = $this->reviewRepository->changeStatus($id);
        return $this->successResponse($reviewData, "User Review Status Updated", Response::HTTP_OK);
    }

}
