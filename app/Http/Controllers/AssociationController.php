<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AssociationService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\AssociationValidator;


class AssociationController extends Controller
{
    use ApiResponser;
   
    protected $AssociationService;
    protected $AssociationValidator;   
    
    
    public function __construct(AssociationService $AssociationService, AssociationValidator $AssociationValidator)
    {
        $this->AssociationService = $AssociationService;
        $this->AssociationValidator = $AssociationValidator;                
    }


    public function getAllData(Request $request)
    {

        $usercontent = $this->AssociationService->getAllData($request);
        return $this->successResponse($usercontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function adduser(Request $request)
     {
     	// Log::info($request);exit;
     	 $data = $request->only([
          'name',
          'email',
          'phone',
          'password',
          'location',
          'president_name',
          'president_phone',
          'general_secretary_name',
          'general_secretary_phone'
        ]);

    	 $usercontent = $this->AssociationValidator->validate($data);


      if ($usercontent->fails()) {
        $errors = $usercontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      try {
        $this->AssociationService->addusercontent($request);
        return $this->successResponse(null, "USER ADDED", Response::HTTP_CREATED);
      }
      catch(Exception $e){
      	// Log::info($e);
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }  

     }
     public function updateuser(Request $request , $id)
     {
     	// Log::info($request);

     	 $data = $request->only([
          'name',
          'email',
          'phone',
          'location',
          'president_name',
          'president_phone',
          'general_secretary_name',
          'general_secretary_phone'
        ]);


     	 $this->AssociationService->updateusercontent($request, $id);
        return $this->successResponse(null,'USER DATA UPDATED' , Response::HTTP_CREATED);

    	 // $usercontent = $this->AssociationValidator->validate($data);


      // if ($usercontent->fails()) {
      //   $errors = $usercontent->errors();
      //   return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      // }      
      // try {
      //   $this->AssociationService->updateusercontent($request, $id);
      //   return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
      // }
      // catch(Exception $e){
      // 	// Log::info($e);
      //   return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      // }  

     }

     public function changePassword(Request $request , $id)
     {
     	// Log::info($request);exit;

     	 $data = $request->only([
          'password'          
        ]);
     	 $this->AssociationService->changePassword($request, $id);
        return $this->successResponse(null,"USER PASSWORD UPDATED", Response::HTTP_CREATED);
     }


     public function changeStatus($id)
     {
      $usercontent = $this->AssociationService->changeStatus($id);
        return $this->successResponse($usercontent,'USER STATUS UPDATED',Response::HTTP_OK);

     }

     public function deleteuser($id)
     {
     	$usercontent = $this->AssociationService->deleteusercontent($id);
        return $this->successResponse($usercontent,'USER DELETED',Response::HTTP_OK);

     }

     
    
     

}