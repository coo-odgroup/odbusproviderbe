<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserBankDetails;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Exception;
use Auth;

class UserController extends Controller
{
  





    //User With user_detail
    // public function getAllUser() {        
    //     $cat = User::with('user_details')->get();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$cat;
    //     return response($output, 200);
    // }


    //User With user bank details
    // public function getAllUser() {        
    //     $cat = User::with('user_bank_details')->get();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$cat;
    //     return response($output, 200);
    // }

    //Add User
    // public function createUser(Request $request) {
    //     // $dt=now();
    //     // $t=date('Y-m-d H:i:s');
    //     // print_r($t); exit;
	//     $chk = User::where('email', '=', $request->email)->first();
	//     if($chk) {
	//       return response()->json([
	//           "message" => "Email already exists!"
	//         ], 404);
	//     } else { 
	//         $userr = new User;
	//         $userr->user_pin = $request->user_pin;
	//         $userr->first_name = $request->first_name;
	//         $userr->middle_name = $request->middle_name;
	//         $userr->last_name = $request->last_name;
	//         $userr->thumbnail = $request->thumbnail;
	//         $userr->email = $request->email;
	//         $userr->user = $request->user;
	//         $userr->org_name = $request->org_name;
	//         $userr->address = $request->address;
	//         $userr->phone = $request->phone;
	//         $userr->alternate_phone = $request->alternate_phone;
	//         $userr->alternate_email = $request->alternate_email;
	//         $userr->password = $request->password;
	//         $userr->user_role = $request->user_role;
	//         $userr->rand_key = Str::random(16);
	//         $userr->created_date = date('Y-m-d H:i:s');;
	//         $userr->created_by = $request->created_by;
	//         $userr->save();
	    
	//         return response()->json(["status" => 1,
	// 			"message" => "New User record created Successfully",
	// 			'result'=>$userr
	//         ], 201);
	//     }
    // }  
      
		/**
     * @var UserService
     */
    protected $userService;

    /**
     * PostController Constructor
     *
     * @param UserService $busTypeService
     *
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        
    }

    
    public function getAllUser() {

        $users = $this->userService->getAll();
        $user ['status']=1;
        $user ['message']='All Data Fetched Successfully';
        $user ['result']=$users;
        return response($user, 200);
    }
     


      public function createUser(Request $request) {
        
        $data = $request->only([
		  	'user_pin', 'first_name', 'middle_name','last_name','thumbnail','email','location','org_name','address','phone','alternate_phone','alternate_email','password', 
        'user_role'
		]);
        
        $userRules = [
          'user_pin' => 'required',
          'first_name' => 'required',
          'middle_name' => '',
          'last_name' => 'required',
          'thumbnail' => 'required',
          'email' => 'required|unique:user',
          'location' => '',
          'org_name' => 'required',
          'address' => 'required',
          'phone' => 'required|max:12',
          'alternate_phone' => '',
          'alternate_email' => '',
          'password' => 'required',
          'user_role' => ''
		];
		$userValidation = Validator::make($data, $userRules);
        
        if ($userValidation->fails()) {
          $errors = $userValidation->errors();
          return $errors->toJson();
        }

        $userBankDetailsRules = [
          'banking_name' => 'required',
          'bank_name' => 'required',
          'ifsc_code' => 'required',
          'account_number' => 'required'

        ];
        $userBankDetails=$request->input('userBankDetails');
      
      // $userValidation = Validator::make($inputs, $userRules);

      foreach($request['userBankDetails'] as $userBankDetails){
        $userCodeValidation = Validator::make($userBankDetails, $userBankDetailsRules);
        if ($userCodeValidation->fails()) {
          // throw new InvalidArgumentException($userCodeValidation->errors()->first());
          $errors = $userCodeValidation->errors();

          return $errors->toJson();
        //   exit;

        }
          
      }   
    
	
        $result = ['status' => 200];
  
        try {
            $result['data'] = $this->userService->savePostData($request);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
      
      } 

      public function getUserbyID($id) {
        //print_r("hello");exit();     
        $users = $this->userService->getById($id);
        $user ['status']=1;
        $user ['message']='Single Data Fetched Successfully';
        $user ['result']=$users;
        return response($user, 200);

	}

  public function getCustomerInformation($id) {
         
    $users = $this->userService->getById($id);
    $user ['status']=1;
    $user ['message']='Single Data Fetched Successfully';
    $user ['result']=$users;
    return response($user, 200);


  }
/////////////////////////////////////////////////////
  public function Login(Request $request) {
    
    $validator = Validator::make($request->all(),
    [
        'email' =>'required|email',
        'password' =>'required|min:5'
    ]
);

if($validator->fails()) {
    return response()->json(["validation_errors" => $validator->errors()]);
}
$output ['data']=$this->userService->login($request);

    $output ['status']=1;
    $output ['message']='Login  Successfull';
    return response($output, 200);
  } 

  public function userDetail() {
        
    $users = $this->userService->userDetail();
    $user ['status']=1;
    $user ['message']='Single Data Fetched Successfully';
    $user ['result']=$users;
    return response($user, 200);

}

}
