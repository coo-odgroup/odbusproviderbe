<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Locationcode;
//use App\Services\LocationService;
use App\Repositories\LocationRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\DB;

use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\LocationValidator;

class LocationController extends Controller
{
    use ApiResponser;
    /**
     * @var LocationService
     */
    //protected $locationService;
    protected $LocationValidator;
    protected $locationRepository;
    
    /**
     * PostController Constructor
     *
     * @param LocationService $busTypeService
     *
     */
    public function __construct(//LocationService $locationService,
                              LocationValidator $LocationValidator,
                                LocationRepository $locationService)
    {
        //$this->locationService = $locationService;
        $this->LocationValidator = $LocationValidator;
        $this->locationRepository = $locationService;
        
    }
    // public function getAllLocations() {

    //     $locations = $this->locationService->getAll();
    //     return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
public function getAllLocations() {

        $locations = $this->LocationRepository->getAll();
        return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getlocationbyID($id) {
      //print_r("hello");exit();     
      try {
        //$locations = $this->locationService->getById($id);
        $locations = $this->locationRepository->getById($id);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    }

    // public function deletelocation ($id) {
    //   try{
    //     $this->locationService->deleteById($id);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    // }
    public function deletelocation($id) {
    try {
        // directly call repository instead of service
        $post = $this->locationRepository->delete($id);

    } catch (Exception $e) {
        DB::rollBack();
        Log::info($e->getMessage());

        return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_PARTIAL_CONTENT);
    }

    return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
}

      
      // public function getLocationDT(Request $request) {      
        
      //   $locations = $this->locationService->getAllLocationDT($request);
      //   return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      // } 

        public function getLocationDT(Request $request) {      
          
          $locations = $this->LocationRepository->getAllLocationDT($request);
          return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
          
        }

      // public function locationsData(Request $request) {      
        
      //   $locations = $this->locationService->locationsData($request);
      //   return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      // }

       public function locationsData(Request $request) {      
        
        $locations = $this->LocationRepository->locationsData($request);
        return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
        
      }

    public function addLocation(Request $request) {
        $data = $request->only([
          'name',
          'synonym','state_id',
          'created_by',
        ]);        
      
        $LocationValidation = $this->LocationValidator->validate($data);
        
        if ($LocationValidation->fails()) {
          $errors = $LocationValidation->errors();
          return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        else
        {
          //$response =  $this->locationService->addPostData($data);;
          $response =  $this->locationRepository->add($data);;
           if($response=='Location Already Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"Location Added Successfully. Waiting for Approval", Response::HTTP_CREATED);
           }
        }
	
    } 


  // public function editLocation(Request $request, $id) {
  //     $data = $request->only([
  //       'name',
  //       'synonym','state_id','url',
  //       'created_by'
  //     ]);    
  
  //     $locationValidation = $this->LocationValidator->validate($data);;
  //     if ($locationValidation->fails()) {
  //       $errors = $locationValidation->errors();
  //       return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
  //     }
  //     else
  //       {
  //         $response =  $this->locationService->editPost($data, $id);
        

  //          if($response=='Location Already Exist')
  //          {
  //             return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
  //          }
  //          else
  //          {
  //              return $this->successResponse($response,"Location Updated", Response::HTTP_CREATED);
  //          }

  //       } 
      
  // }

  public function editLocation(Request $request, $id) {
    $data = $request->only([
        'name',
        'synonym',
        'state_id',
        'url',
        'created_by'
    ]);

    // validate input
    $locationValidation = $this->LocationValidator->validate($data);
    if ($locationValidation->fails()) {
        $errors = $locationValidation->errors();
        return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
    }

    try {
     
        $response = $this->locationRepository->edit($data, $id);

    } catch (Exception $e) {
        DB::rollBack();
        Log::info($e->getMessage());

        return $this->errorResponse(Config::get('constants.RECORD_NOT_FOUND'), Response::HTTP_PARTIAL_CONTENT);
    }

    
    if ($response == 'Location Already Exist') {
        return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
    }

    return $this->successResponse($response, "Location Updated", Response::HTTP_CREATED);
}


  // public function changeStatus ($id) {
    
  //   try{
  //     $this->locationService->changeStatus($id);
  //   }
  //   catch (Exception $e){
  //       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
  //   }
  //   return $this->successResponse(null,"Location Status Updated", Response::HTTP_ACCEPTED);
  // }
      
  public function changeStatus($id) {
    try {
        
        $this->locationRepository->changeStatus($id);

    } catch (Exception $e) {
        DB::rollBack();
        Log::info($e->getMessage());

        return $this->errorResponse(Config::get('constants.UNABLE_CHANGE_STATUS'), Response::HTTP_PARTIAL_CONTENT);
    }

    return $this->successResponse(null, "Location Status Updated", Response::HTTP_ACCEPTED);
}

  // public function filterLocation(request $request) {
  //   $prod= $this->locationService->datafilter($request);
  //   // $output ['status']=1;
  //   // $output ['message']='All Data Fetched Successfully';
  //   // $output ['result']=$prod;
  //   // return response($prod, 200);
  //   return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
  // }

   public function filterLocation(request $request) {
    $prod= $this->LocationRepository->filter($request);
  
    return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
  }

}