<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\UsersService;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use InvalidArgumentException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
   
    use ApiResponser;    
      
    protected $usersService;
    
    
    public function __construct(UsersService $usersService)
    {
        $this->usersService = $usersService;        
    }

    public function login(Request $request) {    

      $user = $this->usersService->login($request);
      return $this->successResponse($user,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK); 
    } 


   

}
