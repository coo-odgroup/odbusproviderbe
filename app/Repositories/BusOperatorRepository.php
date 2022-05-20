<?php

namespace App\Repositories;

use App\Models\BusOperator;
use App\Models\BusStoppage;
use App\Models\Location;
use App\Models\BusCancelled;
use Illuminate\Support\Facades\Log;

class BusOperatorRepository
{
    /**
     * @var BusOperators
     */
    protected $busOperators;
    protected $location;
    protected $busCancelled;
    /**
     * BusOperatorRepository constructor.
     *
     * @param BusOperator $amenities
     */
    public function __construct(BusOperator $busOperators,Location $location,BusCancelled $busCancelled)
    {
        $this->busOperators = $busOperators;
        $this->location = $location;
        $this->busCancelled = $busCancelled;
    }
    public function getAll()
    {
        return $this->busOperators->whereNotIn('status', [2])->orderBy('organisation_name', 'ASC')->get();
    }
    public function BusbyOperatorData($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'];

        $user_role = $request['user_role'] ;
        $user_id = $request['user_id'] ;
        $data= $this->busOperators->whereNotIn('status', [2])
                             ->orderBy('id','DESC');        
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }

        if($name!=null)
        {
            $data = $data->where(
                function($query) use ($name) {
                    return $query->where('email_id','like', '%' . $name . '%')
                                 ->orWhere('operator_name','like', '%' . $name . '%')
                                 ->orWhere('organisation_name','like', '%' . $name . '%')
                                 ->orWhere('contact_number','like', '%' . $name . '%');
            });        
        } 
        if($user_role==5)
        {
            $data= $data->where('user_id',$user_id);   
        }
        $data=$data->paginate($paginate);
        $response = array(
            "count" => $data->count(), 
            "total" => $data->total(),
            "data" => $data
        );   
        return $response;
    }
   
    /**
     * Get amenities by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busOperators
            ->where('id', $id)
            ->get();
    }
    public function getModel($data, BusOperator $busOperators)
    {
        $trim = trim( $data['organisation_name']);
        $remove_space= str_replace(' ', '-', $trim);  
        $remove_special_char = preg_replace('/[^A-Za-z0-9\-]/', '',$remove_space);             
        $url = strtolower($remove_special_char);
        
        $busOperators->email_id = $data['email_id'];
        $busOperators->user_id = $data['user_id'];

        $busOperators->password = $data['password'];
        $busOperators->operator_name = $data['operator_name'];
        $busOperators->operator_url = $url;
        $busOperators->contact_number = $data['contact_number'];
        $busOperators->organisation_name = $data['organisation_name'];
        $busOperators->location_name = $data['location_name'];
        $busOperators->address = $data['address'];
        $busOperators->operator_info = $data['operator_info'];
        $busOperators->additional_email = $data['additional_email'];
        $busOperators->additional_contact = $data['additional_contact'];
        $busOperators->bank_account_name = $data['bank_account_name'];
        $busOperators->bank_name = $data['bank_name'];
        $busOperators->bank_ifsc = $data['bank_ifsc'];
        $busOperators->bank_account_number = $data['bank_account_number'];
        $busOperators->need_gst_bill = $data['need_gst_bill'];
        $busOperators->gst_number = $data['gst_number'];
        $busOperators->gst_amount = $data['gst_amount'];
        $busOperators->created_by = $data['created_by'];

        return $busOperators;
    }
    public function getOperatorEmail($data)
    {
        $email_id=$data['emailid'];
        $id=$data['operator_id'];
        if($id=="")
        {

           $result= $this->busOperators->where('email_id','=',$email_id)->get();
           return count($result);    
        }
        else
        {
            $result= $this->busOperators
            ->where('email_id','=',$email_id)
            ->where('id','!=',$id)
            ->get();    
            return count($result);   
        }
        return $return;
        

    }

    public function getOperatorPhone($data)
    {
        $contact_number=$data['contact_number'];
        $id=$data['operator_id'];
        if($id=="")
        {

           $result= $this->busOperators->where('contact_number','=',$contact_number)->get();
           return count($result);    
        }
        else
        {
            $result= $this->busOperators
            ->where('contact_number','=',$contact_number)
            ->where('id','!=',$id)
            ->get();    
            return count($result);   
        }
        return $return;
        

    }
    public function save($data)
    {   
        $busOperators = new $this->busOperators;
        $busOperators=$this->getModel($data,$busOperators);
        $busOperators->save();
        return $busOperators;
    }

    public function update($data, $id)
    {
        $busOperators = $this->busOperators->find($id);
        $busOperators=$this->getModel($data,$busOperators);
        $busOperators->update();
        return $busOperators;
    }

    public function delete($id)
    {
        $post = $this->busOperators->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }
    public function changeStatus($id)
    {
        $post = $this->busOperators->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    } 
    public function getBusbyOperator($operatorId)
    {

        $operatorWithBuses =  $this->busOperators->with(["bus"=>function($s){$s->where('status',1)->with("ticketPrice"); }])  
         ->where('id', $operatorId)
         ->get('id');
 
        //Log::info($operatorWithBuses);

         $busData = array();
         foreach($operatorWithBuses as $operatorWithBus){
             $buses = $operatorWithBus->bus;
            foreach($buses as $bus)
            {
                //if($bus->status!='1')continue;
                $bStoppages = $bus->ticketPrice;
                if(count($bStoppages)==0)continue;
                
                $sourceId = $bStoppages[0]->source_id;
                $destinationId = $bStoppages[0]->destination_id;
                $source_stoppageName = $this->location->where('id', $sourceId)->get('name');
                $destination_stoppageName = $this->location->where('id', $destinationId)->get('name');
                   

                if(isset($source_stoppageName[0]) && $destination_stoppageName[0]){

                    $busData[] = array(
                        "id" => $bus->id,
                        "name" => $bus->name."[".$bus->bus_number."]"."[".$source_stoppageName[0]['name']."-".$destination_stoppageName[0]['name']."]"  
                    ); 

                }
               
            }          
            
         }
         return $busData;

    }

    public function userOperators($request)
    {
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $result=$this->busOperators
            ->where('id', $id)
            ->whereNotIn('status', [2])
            ->get();    
        }
        else
        {
            if($request['user_role']=="5")
            {
                $result=$this->busOperators
                ->where('user_id', $request['user_id'])
                ->whereNotIn('status', [2])
                ->get();       
            }
            else
            {
                $result=$this->busOperators
                ->whereNotIn('status', [2])
                ->get();    
            }
            
        }
        return $result;    
    }

}