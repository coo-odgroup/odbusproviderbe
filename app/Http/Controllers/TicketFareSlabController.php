<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TicketFareSlabService;
use App\Repositories\TicketFareSlabRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\TicketFareSlabValidator;
use Illuminate\Support\Facades\Log;

class TicketFareSlabController extends Controller
{
  use ApiResponser;
    /**
     * @var LocationService
     */
    protected $ticketFareSlabService;
    protected $ticketFareSlabValidator;
    protected $ticketFareSlabRepository;
    
    /**
     * PostController Constructor
     *
     * @param LocationService $busTypeService
     *
     */
    public function __construct(TicketFareSlabService $ticketFareSlabService,
                              TicketFareSlabValidator $ticketFareSlabValidator,
                              TicketFareSlabRepository $ticketFareSlabRepository)
    {
      $this->ticketFareSlabService = $ticketFareSlabService;
      $this->ticketFareSlabValidator = $ticketFareSlabValidator;
      $this->ticketFareSlabRepository = $ticketFareSlabRepository;

    }
    // public function getAllLocations() {

    //     $locations = $this->locationService->getAll();
    //     return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }

    // public function getlocationbyID($id) {
    //   //print_r("hello");exit();     
    //   try {
    //     $locations = $this->locationService->getById($id);
    //   }
    //   catch (Exception $e) {
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse($locations, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);

    // }

    // public function deletelocation ($id) {
    //   try{
    //     $this->locationService->deleteById($id);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    // }

    //   public function getLocationDT(Request $request) {      

    //     $locations = $this->locationService->getAllLocationDT($request);
    //     return $this->successResponse($locations,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

    //   } 

    // public function ticketFareSlabData(Request $request) {      

    //   $ticketFare = $this->ticketFareSlabService->ticketFareSlabData($request);
    //   return $this->successResponse($ticketFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

    // }

    public function ticketFareSlabData(Request $request) {      
    $data = $request->all();
      $ticketFare = $this->ticketFareSlabRepository->ticketFareSlabData($request);
      return $this->successResponse($ticketFare,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);

    }
    // public function changeStatusticketFareSlab($id) {

    //   try{
    //     $this->ticketFareSlabService->changeStatusticketFareSlab($id);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null,"Location Status Updated", Response::HTTP_ACCEPTED);
    // } 

    public function changeStatusticketFareSlab($id) {

      try{
        $this->ticketFareSlabRepository->changeStatusticketFareSlab($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null,"Location Status Updated", Response::HTTP_ACCEPTED);
    } 
    // public function deleteticketFareSlab($id) {
      
    //   try{
    //     $this->ticketFareSlabService->deleteticketFareSlab($id);
    //   }
    //   catch (Exception $e){
    //     return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
    //   }
    //   return $this->successResponse(null,"Ticket Fare Slab Deleted", Response::HTTP_ACCEPTED);
    // }

     public function deleteticketFareSlab($id) {
      
      try{
        $this->ticketFareSlabRepository->deleteticketFareSlab($id);
      }
      catch (Exception $e){
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }
      return $this->successResponse(null,"Ticket Fare Slab Deleted", Response::HTTP_ACCEPTED);
    }

  //   public function createslab(Request $request) {

  //     $data = $request->all();          
      

  //     $response =  $this->ticketFareSlabService->createslab($data);;

  //     if($response=='Operator Already Exist')
  //     {
  //       return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
  //     }
  //     else
  //     {
  //      return $this->successResponse($response,"Ticket fare Slab Added Successfully", Response::HTTP_CREATED);
  //    }
     
  //  } 

   public function createslab(Request $request) {

      $data = $request->all();          
      

      $response =  $this->ticketFareSlabRepository->createslab($data);;

      if($response=='Operator Already Exist')
      {
        return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
      }
      else
      {
       return $this->successResponse($response,"Ticket fare Slab Added Successfully", Response::HTTP_CREATED);
     }
     
   } 


  // public function editLocation(Request $request, $id) {
  //     $data = $request->only([
  //       'name',
  //       'synonym',
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



  // public function filterLocation(request $request) {
  //   $prod= $this->locationService->datafilter($request);
  //   // $output ['status']=1;
  //   // $output ['message']='All Data Fetched Successfully';
  //   // $output ['result']=$prod;
  //   // return response($prod, 200);
  //   return $this->successResponse($prod,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
  // }

 }