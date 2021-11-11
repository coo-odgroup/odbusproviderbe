<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\UsersService;
use App\Services\UserService;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use App\AppValidator\UserValidator;
use App\AppValidator\AgentDetailsValidator;
use App\AppValidator\LoginValidator;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
   
    use ApiResponser;    
      
    protected $usersService;
    protected $userService;
    protected $userValidator;
    protected $loginValidator;
    protected $agentDetailsValidator;

    public function __construct(UsersService $usersService,UserService $userService,UserValidator $userValidator,LoginValidator $loginValidator,AgentDetailsValidator $agentDetailsValidator)
    {
        $this->usersService = $usersService;
        $this->userService = $userService;
        $this->userValidator = $userValidator;    
        $this->loginValidator = $loginValidator; 
        $this->agentDetailsValidator = $agentDetailsValidator;       
    }

    // public function login(Request $request) {    

    //   $user = $this->usersService->login($request);
    //   return $this->successResponse($user,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    // } 

/////////////////////////Agent Registration//////////////////////////////////////////////////////////
    public function Register(Request $request) {  
      $data = $request->only('phone'); 
                                                     
       $userValidation = $this->userValidator->validate($data);
     
       if ($userValidation->fails()) {
         $errors = $userValidation->errors();
         return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
       }
       try {
         $response = $this->userService->Register($request);
         if($response!='Registered Agent')
         {
            return $this->successResponse($response,Config::get('constants.OTP_GEN'),Response::HTTP_OK);
         }else{
            return $this->errorResponse($response,Response::HTTP_OK);
         }
       }
       catch (Exception $e) {
         return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
       }      
    } 


    public function verifyOtp(Request $request) 
    {
     $data = $request->all();
     $verify = $this->userService->verifyOtp($request);
     if($verify == ''){
       return $this->errorResponse(Config::get('constants.OTP_NULL'),Response::HTTP_OK);
     }elseif($verify == 'Inval OTP'){
      return $this->errorResponse(Config::get('constants.OTP_INVALID'),Response::HTTP_OK);
     }
     else{
     return $this->successResponse($verify,Config::get('constants.VERIFIED'),Response::HTTP_OK);
     }
    }
   public function login(Request $request) {  
    
    $data = $request->only([
                              'email','password','user_type'
                            ]); 
    $loginValidation = $this->loginValidator->validate($data);
  
    if ($loginValidation->fails()) {
      $errors = $loginValidation->errors();
      return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
    }
    try {

      $response = $this->userService->login($request);
      switch($response){
          case('un_registered_agent'):   //Agent is not registered
              return $this->errorResponse(Config::get('constants.UNREGISTERED'),Response::HTTP_OK);
          break;
          case('pwd_mismatch'):     //Password Mismatch
              return $this->errorResponse(Config::get('constants.PWD_MISMATCH'),Response::HTTP_OK);   
          break;
          case('agent_role_mismatch'):   //Role of the agent mismatches
              return $this->errorResponse(Config::get('constants.ROLE_MISMATCH'),Response::HTTP_OK);
          break;
      }
      return $this->successResponse($response,Config::get('constants.LOGIN_SUCCESSFUL'),Response::HTTP_OK);     
      }
      catch (Exception $e) {
       return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      } 
    } 

    public function getRoles() {
      $roles = $this->userService->getRoles();
      return $this->successResponse($roles,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }

    public function agentRegister(Request $request) {

      $data = $request->all();
      $agentDetailsValidation = $this->agentDetailsValidator->validate($data);
     
      if ($agentDetailsValidation->fails()) {
        $errors = $userValidation->errors();
        return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
      }
      try {
        $agent = $this->userService->agentRegister($request);
      return $this->successResponse($agent,Config::get('constants.REGT_SUCCESS'),Response::HTTP_OK);
      }
      catch (Exception $e) {
        return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
      }        
    }


    public function getallAgent()
    {
        $wallet = $this->userService->getallAgent();
        return $this->successResponse($wallet,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    }
}
