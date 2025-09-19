<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Services\UserContentService;
use App\Repositories\UserContentRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\UserContentValidator;


class UserContentController extends Controller
{
    use ApiResponser;
   
    //protected $userContentService;
    protected $userContentValidator;  
    protected $userContentRepository; 
    
    
    public function __construct(//UserContentService $userContentService,
                               UserContentValidator $userContentValidator,
                               UserContentRepository $userContentRepository)
    {
        //$this->userContentService = $userContentService;
        $this->userContentValidator = $userContentValidator; 
        $this->userContentRepository = $userContentRepository;               
    }


   //  public function getAllData(Request $request)
   //  {

   //      $usercontent = $this->userContentService->getAllData($request);
   //      return $this->successResponse($usercontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
   //  }

   public function getAllData(Request $request)
    {

        $usercontent = $this->userContentRepository->getAllData($request);
        return $this->successResponse($usercontent,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

     public function adduser(Request $request)
     {
     	// Log::info($request);exit;
     	 $data = $request->only([
          'name',
          'bus_operator_id',
          'email',
          'phone',
          'password'
        ]);

    	 $usercontent = $this->userContentValidator->validate($data);


      if ($usercontent->fails()) {
        $errors = $usercontent->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }      
      else
        {
          //$response = $this->userContentService->addusercontent($request);
          $response = $this->userContentRepository->addusercontent($request);

           if($response=='Phone Number Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           elseif($response=='Email Id Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           elseif($response=='Bus Operator Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"USER ADDED", Response::HTTP_CREATED);
           }
        }

     }
     public function updateuser(Request $request , $id)
     {
     	// Log::info($id);exit;

     	 $data = $request->only([
          'name',
          'email',
          'phone',
        ]);


     	 // $this->userContentService->updateusercontent($request, $id);
       //  return $this->successResponse(null,'USER DATA UPDATED' , Response::HTTP_CREATED);


          //$response = $this->userContentService->updateusercontent($request, $id);
          $response = $this->userContentRepository->updateusercontent($request, $id);

           if($response=='Phone Number Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           elseif($response=='Email Id Exist')
           {
              return $this->errorResponse($response,Response::HTTP_PARTIAL_CONTENT);
           }
           else
           {
               return $this->successResponse($response,"USER DATA UPDATED", Response::HTTP_CREATED);
           }
 
    	 // $usercontent = $this->userContentValidator->validate($data);


      // if ($usercontent->fails()) {
      //   $errors = $usercontent->errors();
      //   return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      // }      
      // try {
      //   $this->userContentService->updateusercontent($request, $id);
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
     	 //$this->userContentService->changePassword($request, $id);
       $this->userContentRepository->changePassword($request,$id);
        return $this->successResponse(null,"USER PASSWORD UPDATED", Response::HTTP_CREATED);
     }


   //   public function changeStatus($id)
   //   {
   //    $usercontent = $this->userContentService->changeStatus($id);
   //      return $this->successResponse($usercontent,'USER STATUS UPDATED',Response::HTTP_OK);

   //   }
   public function changeStatus($id)
     {
      $usercontent = $this->userContentRepository->changeStatus($id);
        return $this->successResponse($usercontent,'USER STATUS UPDATED',Response::HTTP_OK);

     }

   //   public function deleteuser($id)
   //   {
   //   	$usercontent = $this->userContentService->deleteusercontent($id);
   //      return $this->successResponse($usercontent,'USER DELETED',Response::HTTP_OK);

   //   }
       public function deleteuser($id)
       {
       	$usercontent = $this->userContentRepository->deleteusercontent($id);
         return $this->successResponse($usercontent,'USER DELETED',Response::HTTP_OK);

       }
    
     

}