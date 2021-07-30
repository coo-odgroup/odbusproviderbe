<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SeatOpenService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\LocationValidator;

class SeatOpenController extends Controller
{
    use ApiResponser;
   
    protected $seatopenService;
    protected $LocationValidator;
    
    
    public function __construct(SeatOpenService $seatopenService,LocationValidator $LocationValidator)
    {
        $this->seatopenService = $seatopenService;
        $this->LocationValidator = $LocationValidator;
        
    }

    public function getAllseatopen()
    {
        $seatopen = $this->seatopenService->getAll();
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function addseatopen(Request $request)
     {


        $seatopen = $this->seatopenService->addseatopen($request);
            return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

     }
     public function updateseatopen(Request $request, $id)
     {

      // Log::info($request); exit;
        $seatopen = $this->seatopenService->updateseatopen($request, $id);
            return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

     }


      public function getseatopenDT(Request $request) {      
        
        $seatopen = $this->seatopenService->getseatopenDT($request);
        return $this->successResponse($seatopen,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

      public function changeStatus ($id) {
    
        try{
          $this->seatopenService->changeStatus($id);
        }
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_ACCEPTED);
      }

      public function deleteseatopen ($id) {
      try{
        $this->seatopenService->deleteById($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    




    public function getlocationbyID($id) {
      //print_r("hello");exit();     
      try {
        $locations = $this->locationService->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    public function deletelocation ($id) {
      try{
        $this->locationService->deleteById($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }
      
     

    public function addLocation(Request $request) {
        $data = $request->only([
          'name',
          'synonym',
          'created_by',
        ]);

        
      
        $LocationValidation = $this->LocationValidator->validate($data);
        
        if ($LocationValidation->fails()) {
          $errors = $LocationValidation->errors();
          // return $errors->toJson();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        
    
        try {
            $this->locationService->addPostData($data);
            return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        } 
  
    } 


  public function editLocation(Request $request, $id) {
      $data = $request->only([
        'name',
        'synonym',
        'created_by'
      ]);
    
      
  
      $locationValidation = $this->LocationValidator->validate($data);;
      if ($locationValidation->fails()) {
        $errors = $locationValidation->errors();
        // return $errors->toJson();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }

      
      try {
        $this->locationService->editPost($data, $id);
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      }
      catch(Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  
      
  }

  
      
  public function filterLocation(request $request) {
    $prod= $this->locationService->datafilter($request);
    // $output ['status']=1;
    // $output ['message']='All Data Fetched Successfully';
    // $output ['result']=$prod;
    // return response($prod, 200);
    return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
  }

}