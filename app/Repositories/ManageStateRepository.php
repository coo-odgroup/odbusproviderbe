<?php
namespace App\Repositories;
use App\Models\State;
// use App\Models\Locationcode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class ManageStateRepository
{
    /**
     * @var Location
     */
    protected $state;

    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(State $state)
    {
        $this->state = $state;
    }
   

    public function statelist()
    {

        return $data= $this->state->where('status', 1)->orderBy('status','ASC')->orderBy('state_name','ASC')->get();
    }

    public function getAllstate($request)
    {
        $paginate = $request['rows_number'] ;
        $name = $request['name'] ;

        $data= $this->state->whereNotIn('status', [2])->orderBy('status','ASC')->orderBy('state_name','ASC');


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
                    $data = $query->where(function($query) use ($name) {
                        $query->where('state_name','like', '%' .$name . '%')
                        ->orWhere('created_by','like', '%' .$name . '%')->whereNotIn('status', [2]);
                    });
            });                            
        }     
        $data=$data->paginate($paginate);
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "test" => "hello",
            "data" => $data
           );   
           return $response; 
    }


     public function getModel($data, State $state)
    { 
        $trim = trim( $data['state_name']);
        $remove_space= str_replace(' ', '-', $trim);  
        // $remove_space= str_replace(' ', '', $trim);  
        $remove_special_char = preg_replace('/[^A-Za-z0-9\-]/', '',$remove_space);             
        $url = strtolower($remove_special_char);


      $state->state_name = $data['state_name'];
      $state->state_url = $url;
      $state->created_by = $data['created_by'];
      return $state;
    }


    public function createState($data){
    	$existingstate = $this->state
                                 ->where('state_name',$data['state_name']) 
                                 ->where('status','!=',2)
                                 ->get();
        if(count($existingstate) == 0)
        {
              $state = new $this->state;
              $state=$this->getModel($data,$state);
              $state->save();
              return $state;
        }  
        else
        {
            return 'State Already Exist';
        }        
    }
    public function updateState($data, $id)
    {    
    	// log::info($id);
        $duplicate_data = $this->state
                               ->where('state_name','like', $data['state_name'])
                               ->where('id','!=',$id )
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        { 
            $state = $this->state->find($id);
            $state=$this->getModel($data,$state);
            $state->update();
            return $state;
        }  
        else
        {
            return 'State Already Exist';
        } 
    } 





    public function changeStatus($id)
    {
        $state = $this->state->find($id);
        if($state->status==0){
            $state->status = 1;
        }elseif($state->status==1){
            $state->status = 0;
        }
        $state->update();
        return $state;
    }
    

}