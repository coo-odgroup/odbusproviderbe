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
use App\Jobs\SendForgetOtpEmailJob;
use App\Jobs\SendResetPasswordEmailJob;
use App\Models\Agent;
use App\Jobs\SendAgentRequestToUserEmailJob;
use App\Jobs\SendAgentRequestToAdminEmailJob;

class UserRepository
{
    /**
     * @var User
     */
    protected $user;
    protected $userBankDetails;
    protected $channelRepository;
    protected $agent;
    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(User $user, Agent $agent, UserBankDetails $userBankDetails,ChannelRepository $channelRepository)
    {
        $this->user = $user;
        $this->userBankDetails = $userBankDetails;
        $this->channelRepository = $channelRepository; 
        $this->agent = $agent;
    }

    
    public function getAll()
    {

        return $this->user::with('userBankDetails')->get();
    }

    public function getallAgent()
    {

        return $this->user->where('user_type','Agent')->where('status',1)->get();
    }

    public function allApiClient()
    {

        return $this->user->where('user_type','API USER')->where('role_id',6)->where('status',1)->get();
    }

    
    public function getById($id)
    {
        return $this->user::with('userBankDetails')->where('id', $id)->get();
    }

    public function AllUser(){

        return $this->user->where('name','!=' ,null)->where('email','!=' ,null)->get();
    }

    public function AgentVerifyOtp($data){

        $exist= $this->user->where('email', $data['email'])->get();

        if(isset($exist[0])){

            if($data['otp'] == $exist[0]->otp ){
        
               $user = $this->user->find($exist[0]->id);       
               $user->otp = null;
               $user->update(); 
               return $user;

            }else{
                return 'INVALID OTP';
            }
 
        }else{
            return "NOT FOUND";
        }

    }

    public function AgentResetPassword($data){

        $exist= $this->user->where('email', $data['email'])->get();

        if(isset($exist[0])){
 
         $email= $exist[0]->email;
         $phone=  $exist[0]->phone;
         $name=  $exist[0]->name;
 
 
        // Log::info($otp);
 
        $user = $this->user->find($exist[0]->id);       
        $user->password =bcrypt($data['password']);
        $user->update();
         
         $today=date("Y-m-d H:i:s");
 
         $subject = "Reset Password is successful - ".$today;
         $emailData['password']= $data['password'] ;
         $emailData['name']= $name ;
 
         SendResetPasswordEmailJob::dispatch($email,$subject, $emailData);
 
         //$sendsms = $this->channelRepository->sendSms($request,$otp); 
 
         return $user;
 
        }else{
            return "NOT FOUND";
        }
 

    }

    public function AgentForgetPasswordOtp($data){

       $exist= $this->user->where('email', $data['email'])->get();

       if(isset($exist[0])){

        $email= $exist[0]->email;
        $phone=  $exist[0]->phone;
        $name=  $exist[0]->name;

        $otp=  rand(100000,999999);

       // Log::info($otp);

       $user = $this->user->find($exist[0]->id);       
       $user->otp = $otp;
       $user->update();
        
        $today=date("Y-m-d H:i:s");

        $subject = "Forgot Password OTP - ".$today;
        $emailData['otp']= $otp ;
        $emailData['name']= $name ;

        SendForgetOtpEmailJob::dispatch($email,$subject, $emailData);

        //$sendsms = $this->channelRepository->sendSms($request,$otp); 

        return $user;

       }else{
           return "NOT FOUND";
       }

        

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
        $request->request->add(['name' => 'Agent']);
          // ->where('status', '1');

        $registeredAgent = $query->exists();

        if(!$registeredAgent){
            $agent = new $this->user; 
            $otp = $this->sendOtp($request);
            $agent->phone = $request['phone'];
            $agent->role_id = '3';
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
        //return $sendsms; 
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
        $sts=$query->first()->status; 
        if($sts == 1)
        {
            $password = $query->first()->password; 

            if(Hash::check($request['password'], $password )){
                $role = $query->first()->role_id;

                if($role == $request['user_type']){ 

                // return $query->first(); 
                    if($request['user_type']==4)
                    {
                        return $query->with('busOperator')->first();
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

        }else
        {
            return "inactive_user";
        }

    }else{
        return "un_registered_agent";
    }    
}

public function getRoles()
{         
  return Role::whereNotIn('status', [2])->where('id','!=', 3)->get();
}

public function agentRegister($request){  

    // log::info($request);

    $email = $this->agent->where('email',$request['email'])->where('status','!=',2)->get();
    // $phone = $this->agent->where('phone',$request['phone'])->where('status',1)->get();
    $aadhaar = $this->agent->where('adhar_no',$request['adhar_no'])->where('status','!=',2)->get();
    $pancard = $this->agent->where('pancard_no',$request['pancard_no'])->where('status','!=',2)->get();


    
    if(count($email)==0)
    {
            if(count($aadhaar)==0)
            {
                if(count($pancard)==0)
                {
                        $users = $this->user->find($request['userId']);
                        $users->name = $request['name'];
                        $users->email = $request['email'];
                        $users->password = bcrypt($request['password']);
                        $users->user_type = 'AGENT';
                        $users->role_id = 3;
                        $users->location = $request['location'];
                        $users->adhar_no = $request['adhar_no'];
                        $users->pancard_no = $request['pancard_no'];
                        $users->organization_name = $request['organization_name'];
                        $users->address = $request['address'];
                        $users->street = $request['street'];
                        $users->landmark = $request['landmark'];
                        $users->city = $request['city'];
                        $users->pincode = $request['pincode'];
                        $users->name_on_bank_account = $request['name_on_bank_account'];
                        $users->bank_name = $request['bank_name'];
                        $users->ifsc_code = $request['ifsc_code'];
                        $users->bank_account_no = $request['bank_account_no'];
                        $users->branch_name = $request['branch_name'];
                        $users->upi_id = $request['upi_id'];
                        $users->email = $request['email'];
                        $agent->agent_type = 1;
                        $users->status = 0;

                        $users->update();                       

                        $to_user = $request['email'];
                        $subject = "Agent Creation Request Email";
                        $agentData= [
                            'userName'=>$request['name'],
                            'userEmail'=> $request['email']                        
                           ] ;
                        SendAgentRequestToUserEmailJob::dispatch($to_user, $subject, $agentData);

                        $to_admin ='agent@odbus.in';                                       
                        SendAgentRequestToAdminEmailJob::dispatch($to_admin, $subject, $agentData);
                        // return $agent;
                        // $usersDetails = $this->user->where('id',  $request['userId'])->get();
                        // return $usersDetails; 
                        return $users; 
                }
                else
                {
                    return 'Pan Card Already Exist';
                }
            }
            else
            {
                return 'Aadhaar Card Already Exist';
            }
    }
    else
    {
        return 'Email Already Exist';
    }
    
}



}
