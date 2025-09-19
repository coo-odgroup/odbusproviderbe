<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoardingDroping;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use App\Services\BoardingDropingService;
use App\Repositories\BoardingDropingRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BoardingDropingValidator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BoardingDropingController extends Controller
{
    use ApiResponser;
    protected $boardingDropingService;
    protected $boardingDropingValidator;
    protected $boardingDropingRepository;

    
    public function __construct(BoardingDropingService $boardingDropingService,
                                BoardingDropingValidator $boardingDropingValidator,
                                boardingDropingRepository $boardingDropingRepository)
    {
        $this->boardingDropingService = $boardingDropingService;
        $this->boardingDropingValidator = $boardingDropingValidator;
        $this->boardingDropingRepository = $boardingDropingRepository;
    }

    // public function getAllBoardingDroping() {

    //     $boardingdroping = $this->boardingDropingService->getAll();
    //     return $this->successResponse($boardingdroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // }

     public function getAllBoardingDroping() {

        $boardingdroping = $this->boardingDropingRepository->getAll();
        return $this->successResponse($boardingdroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }

  //   public function createBoardingDroping(Request $request) {
  //       //   log::info($request);
  //       // exit();
  //       $data = $request->only([
  //         'location_id', 
  //         'boarding_point',
  //         'created_by', 
  //       ]);
  //       $boardingdropingValidation = $this->boardingDropingValidator->validate($data);
         

  //       if ($boardingdropingValidation->fails()) {
  //         $errors = $boardingdropingValidation->errors();
  //         return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
  //       }

  //       try {
  //         $response = $this->boardingDropingService->savePostData($data);
          

  //         return $this->successResponse($response,"Bus Stoppage Added ", Response::HTTP_CREATED); 
  //       } catch (Exception $e) {
  //         return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
  //       }  
  //  } 

  public function createBoardingDroping(Request $request) {
    $data = $request->only([
        'location_id', 
        'boarding_point',
        'created_by', 
    ]);

    
    $boardingdropingValidation = $this->boardingDropingValidator->validate($data);

    if ($boardingdropingValidation->fails()) {
        $errors = $boardingdropingValidation->errors();
        return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
    }

    try {
       
        $response = $this->boardingDropingRepository->save($data);

        return $this->successResponse(
            $response,
            "Bus Stoppage Added",
            Response::HTTP_CREATED
        );
    } catch (Exception $e) {
        return $this->errorResponse(
            $e->getMessage(),
            Response::HTTP_PARTIAL_CONTENT
        );
    }
}



   


    public function updateBoardingDroping(Request $request, $id) {
        $data = $request->only([
            'location_id',
            'boarding_point', 
           // 'dropping_point',
            'created_by'
        ]);
       
        $boardingdropingValidation = $this->boardingDropingValidator->validate($data);

        if ($boardingdropingValidation->fails()) {
            $errors = $boardingdropingValidation->errors();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
          }
        try {
          //$response = $this->boardingDropingService->updatePost($data, $id);
          $response = $this->boardingDropingRepository->update($data, $id);
          return $this->successResponse($response, "Bus Stoppage Updated", Response::HTTP_CREATED);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
    }

    // public function deleteBoardingDroping ($id) {
    //   try {
    //     $response = $this->boardingDropingService->deleteById($id);
    //     return $this->successResponse($response, "Bus Stoppage Deleted", Response::HTTP_ACCEPTED);
    //   } catch (Exception $e) {
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    // }


    public function deleteBoardingDroping ($id) {
      try {
        $response = $this->boardingDropingRepository->delete($id);
        return $this->successResponse($response, "Bus Stoppage Deleted", Response::HTTP_ACCEPTED);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }

    // public function getBoardingDroping($id) {
    //     try {
    //   $boardingDropingID = $this->boardingDropingService->getById($id);
    //     }
    //     catch (Exception $e) {
    //         return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //       }
    //       return $this->successResponse($boardingDropingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);    
	     
    //     }

    public function getBoardingDroping($id) {
        try {
      $boardingDropingID = $this->boardingDropingRepository->getById($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
          }
          return $this->successResponse($boardingDropingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);    
	     
        }

        // public function getBoardingDropingbyLoacationId($id) {
        //   try {
        // $boardingDropingID = $this->boardingDropingService->getByLocationId($id);
        //   }
        //   catch (Exception $e) {
        //       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        //     }
        //     return $this->successResponse($boardingDropingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);    
         
        //   }

         public function getBoardingDropingbyLoacationId($id) {
          try {
        $boardingDropingID = $this->boardingDropingRepository->getByLocationId($id);
          }
          catch (Exception $e) {
              return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
            }
            return $this->successResponse($boardingDropingID,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);    
         
          }
        ////data table//////
    // public function getBoardingDropingDT(Request $request) {      
        
    //     $boardingDroping = $this->boardingDropingService->getBoardingDropingDT($request);
    //     return $this->successResponse($boardingDroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    //   }

    public function getBoardingDropingDT(Request $request) {      
        
        $boardingDroping = $this->boardingDropingRepository->getBoardingDropingDT($request);
        return $this->successResponse($boardingDroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }

    // public function boardingData(Request $request) {     
        
    //     $boardingDroping = $this->boardingDropingService->boardingData($request);
    //     return $this->successResponse($boardingDroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    //   }

    public function boardingData(Request $request) {     
        
        //$boardingDroping = $this->boardingDropingService->boardingData($request);
        $boardingDroping = $this->boardingDropingRepository->boardingData($request);
        return $this->successResponse($boardingDroping,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }
      
      public function createBoarding(Request $request) 
      {      

        $data = $request->only([
          'location_id', 
          'name',
          'type',
          'created_by',
        ]);
        $boardingdropingValidation = $this->boardingDropingValidator->validate($data);
      
      if ($boardingdropingValidation->fails()) {
          $errors = $boardingdropingValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
          $this->boardingDropingService->createBordingDroping($data);
      } catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
      }
      return $this->successResponse($data,Config::get('constants.RECORD_ADDED'),Response::HTTP_CREATED); 
    } 


    // public function changeStatus ($locationId) {
    
    //   try{
    //     $response = $this->boardingDropingService->changeStatus($locationId);
       
    //     return $this->successResponse($response, "Bus Stoppage  Status Updated", Response::HTTP_ACCEPTED);
    //   }
    //   catch (Exception $e){
    //       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    // }

    public function changeStatus ($locationId) {
    
      try{
        $response = $this->boardingDropingRepository->changeStatus($locationId);
       
        return $this->successResponse($response, "Bus Stoppage  Status Updated", Response::HTTP_ACCEPTED);
      }
      catch (Exception $e){
          return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
    }
}
