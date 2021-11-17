<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ChannelRepository;
use App\Models\User;
use App\Models\Role;
use App\Models\UserBankDetails;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;
    protected $userBankDetails;
    protected $channelRepository;
    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(User $user, UserBankDetails $userBankDetails,ChannelRepository $channelRepository)
    {
        $this->user = $user;
        $this->userBankDetails = $userBankDetails;
        $this->channelRepository = $channelRepository; 
    }

    
    public function getAll()
    {
         
        return $this->user::with('userBankDetails')->get();
    }

    public function getallAgent()
    {
         
        return $this->user->where('user_type','Agent')->where('status',1)->get();
    }

    
    public function getById($id)
    {
        return $this->user::with('userBankDetails')->where('id', $id)->get();
    }

    
    
    public function save($data)
    {
        $user = new $this->user;
        $user->user_pin = $data['user_pin'];
        $user->first_name= $data['first_name'];
        $user->middle_name= $data['middle_name'];
        $user->last_name= $data['last_name'];
        $user->thumbnail= $data['thumbnail'];
        $user->email= $data['email'];
        $user->location= $data['location'];
        $user->org_name= $data['org_name'];
        $user->address= $data['address'];
        $user->phone= $data['phone'];
        $user->alternate_phone= $data['alternate_phone'];
        $user->alternate_email= $data['alternate_email'];
        $user->password= $data['password'];
        $user->user_role= $data['user_role'];
        $user->rand_key= Str::random(16);
        $user->last_login= date('Y-m-d H:i:s');
        $user->created_by= "Admin";
        $user->save();

        foreach($data['userBankDetails'] as $user_code){
          $locdetRecord = new $this->userBankDetails;   
          $locdetRecord->user_id = $user->id;     
          $locdetRecord->banking_name =  $user_code['banking_name'];
          $locdetRecord->bank_name =  $user_code['bank_name'];
          $locdetRecord->ifsc_code =  $user_code['ifsc_code'];
          $locdetRecord->account_number =  $user_code['account_number'];
          $locdetRecord->bank_name =  $user_code['bank_name'];
          $locdetRecord->created_by =  "Admin";
          $user->userBankDetails[] = $locdetRecord;
            
        }   
        $user->push();
        return $user->fresh();
    }

    public function update($data, $id)
    {
        
        if ($this->user::where('id', $id)->exists()) 
        {
            $user = $this->user->find($id);
            $user->name =is_null($data['name']) ? $user->name : $data->name;     
            $user->synonym = is_null($data['synonym']) ? $user->synonym : $data->synonym; 
            $user->created_by = is_null($data['created_by']) ? $user->created_by : $data->created_by; 
            
            $user->save();
            //var_dump($request->sub_catagories);
            //exit;

             foreach ($data['userBankDetails'] as $user_code)
             {
                $locdetRecord = new $this->userBankDetails; 
                
                //Check if param comes in Request.If Yes than value is param value else value is exist value.
                
                $locdetRecord->user_id =  $user_code['user_id'];
                $locdetRecord->type =  is_null($user_code['type']) ? $user->type : $user_code['type'];
                $locdetRecord->providerid =  is_null($user_code['providerid']) ? $user->providerid : $user_code['providerid'];
                $locdetRecord->created_by =  is_null($user_code['created_by']) ? $user->created_by : $user_code['created_by'];
                $user->userBankDetails[] = $locdetRecord;
              }


           $user->push();
          return $user->fresh();

        }

    }
    public function delete($id)
    {
        
        /*$userBankDetails = $this->userBankDetails->find($id);
        $userBankDetails->delete();
        return $userBankDetails;
        $user */
    }
/////////******************///////////
///////**customerInformation***/////////
    public function getCustomerInformation($id){
   
        $customer = $this->user->where('id', $id)->get();
        //return  $customer;
        $post = json_encode($customer);
        return $post;
     
    }
    ////////////***//////////////
    //////User Login//////////
    // public function Login($data)
    // {
    //    // $accessToken = auth()->$this->user()->createToken('authToken')->accessToken;
       
    //         //Auth::attempt(['email' => $data->email, 'password' => $data->password]);
    //        // var_dump($this->user);
    //     $user = Auth::user();
    //     //$Token = $user->createToken('Token')->accessToken;

    //     //return response([ 'Token' => $accessToken]);
    //     $user = User::where('email' ,$data->email)->where( 'password' , $data->password)->first();
    //     if($user){
    //         $success['token'] =  $user->createToken('Token')->accessToken;
    //         return response(['user' => [
    //                 'email'=>$data->email
    //             ],'Token' => $success['token']], "200");
    //     }else{
    //         return response(['error'=>'Unauthorised'], 401);
    //     }
            
    //     //return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    // }
    public function userDetail()
    {
        $user = Auth::user();
        if(!is_null($user)) {
            return response(['user' => $user]);
        }
        else {
            return response()->json(["status" => "failed",  "message" => "Whoops! no user found"]);
        }
    }

    ///////////////////////////////////////////////Agent Registration////////////////////////////////////////////////////////////

    public function Register($request)
    {   
        $query =$this->user->where([
            ['phone', $request['phone']]  
        ]);
          // ->where('status', '1');
           
    $registeredAgent = $query->exists();
    
    if(!$registeredAgent){
        $agent = new $this->user; 
        $otp = $this->sendOtp($request);
        $agent->phone = $request['phone'];
        $agent->otp = $otp;
        $agent->save();
        return $agent;
    }else{
        $status = $this->user->where('phone',$request['phone'])->first()->status;
            switch($status){
                case '0':
                    $otp = $this->sendOtp($request);
                    $this->user->where('phone', $request['phone'])->update(array(
                        'otp' => $otp
                         ));
                    $agent = $this->user->where('phone', $request['phone'])->get();
                    return $agent[0]; 
                case '1':
                    return "Registered Agent";
            }
           
         }
    
}
public function sendOtp($request){
    $otp = rand(10000, 99999);
        $sendsms = $this->channelRepository->sendSms($request,$otp);  
    return  $otp;
}
public function verifyOtp($request){

    $rcvOtp = trim($request['otp']);
    $userId = $request['userId'];
    $existingOtp = $this->user->where('id', $userId)->get('otp');
    $existingOtp = $existingOtp[0]['otp'];
    $user = $this->user->where('id', $userId)->first()->only('name');
    if(($rcvOtp=="")){
        return "";
        }
    elseif($existingOtp == $rcvOtp){

         $users = $this->user->where('id', $userId)->update(array( 'otp' => Null, ));   
         $usersDetails = $this->user->where('id', $userId)->get();
        return $usersDetails; 
    }
    else{
        return 'Inval OTP';
    }
}
public function login($request){
    $query =$this->user->where([
        ['email', $request['email']],
        ['email', '<>', null]
      ]);
    $existingUser = $query->latest()->exists(); 
    
    if($existingUser == true){
        $password = $query->first()->password; 

        if(Hash::check($request['password'], $password )){
            $role = $query->first()->role_id;
            
            if($role == $request['user_type']){  
                if($request['user_type']==4)
                {
                    return $query->with('UserBusOperator.BusOperator')->first();
                }
                else{
                    return $query->first();
                }
                
            }else{
                return "agent_role_mismatch";
            }   

        } else{
            return "pwd_mismatch";
        }     
    }else{
        return "un_registered_agent";
    }    
  }

  public function getRoles()
  {
      return Role::whereNotIn('status', [2])->get();
  }

  public function agentRegister($request)

    {    
        $users = $this->user->where('id', $request['userId'])->update(array(
                            'name' => $request['name'],
                            'email' => $request['email'],
                            'password' => bcrypt($request['password']),
                            'user_type' => 'AGENT',
                            'role_id' => '3',
                            'location' => $request['location'],
                            'adhar_no' => $request['adhar_no'],
                            'pancard_no' => $request['pancard_no'],
                            'organization_name' => $request['organization_name'],
                            'address' => $request['address'],
                            'street' => $request['street'],
                            'landmark' => $request['landmark'],
                            'city' => $request['city'],
                            'pincode' => $request['pincode'],
                            'name_on_bank_account' => $request['name_on_bank_account'],
                            'bank_name' => $request['bank_name'],
                            'ifsc_code' => $request['ifsc_code'],
                            'bank_account_no' => $request['bank_account_no'],
                            'branch_name' => $request['branch_name'],
                            'upi_id' => $request['upi_id'],
                            'email' => $request['email'],
                            'status' => "1",
                            ));
         $usersDetails = $this->user->where('id',  $request['userId'])->get();
         return $usersDetails; 

    
    }

}
