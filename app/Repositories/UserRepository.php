<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\UserBankDetails;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class UserRepository
{
    /**
     * @var User
     */
    protected $user;
    protected $userBankDetails;

    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(User $user, UserBankDetails $userBankDetails)
    {
        $this->user = $user;
        $this->userBankDetails = $userBankDetails;
        
    }

    
    public function getAll()
    {
         
        return $this->user::with('userBankDetails')->get();
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
    public function Login($data)
    {
       // $accessToken = auth()->$this->user()->createToken('authToken')->accessToken;
       
            //Auth::attempt(['email' => $data->email, 'password' => $data->password]);
           // var_dump($this->user);
        $user = Auth::user();
        //$Token = $user->createToken('Token')->accessToken;

        //return response([ 'Token' => $accessToken]);
        $user = User::where('email' ,$data->email)->where( 'password' , $data->password)->first();
        if($user){
            $success['token'] =  $user->createToken('Token')->accessToken;
            return response(['user' => [
                    'email'=>$data->email
                ],'Token' => $success['token']], "200");
        }else{
            return response(['error'=>'Unauthorised'], 401);
        }
            
        //return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }
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



}
 