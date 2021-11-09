<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\AgentNotificationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class AgentNotificationService
{
	
	protected $agentNotificationRepository;

	
	public function __construct(AgentNotificationRepository $agentNotificationRepository)
	{
		$this->agentNotificationRepository = $agentNotificationRepository;
	} 

	public function getData($request)
	{
		$paginate = $request['rows_number'] ;
		$name = $request['name'] ;
		$user_id = $request['user_id'] ;

		$start_date="";
		$end_date="";
		$rangeFromDate  =  $request->rangeFromDate;
		$rangeToDate  =  $request->rangeToDate;

		if(!empty($rangeFromDate))
		{
			if(strlen($rangeFromDate['month'])==1)
			{
				$rangeFromDate['month']="0".$rangeFromDate['month'];
			}
			if(strlen($rangeFromDate['day'])==1)
			{
				$rangeFromDate['day']="0".$rangeFromDate['day'];
			}

			$start_date = $rangeFromDate['year'].'-'.$rangeFromDate['month'].'-'.$rangeFromDate['day'] ;     
		}

		if(!empty($rangeToDate))
		{
			if(strlen($rangeToDate['month'])==1)
			{
				$rangeToDate['month']="0".$rangeToDate['month'];
			}
			if(strlen($rangeToDate['day'])==1)
			{
				$rangeToDate['day']="0".$rangeToDate['day'];
			}

			$end_date = $rangeToDate['year'].'-'.$rangeToDate['month'].'-'.$rangeToDate['day'] ;     
		}


		$data= $this->agentNotificationRepository->getallnotification($user_id);

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
			$data = $this->agentNotificationRepository->Filter($data, $name);                     
		}    

		if($start_date != null && $end_date != null)
		{
			$data = $this->agentNotificationRepository->dateFilter($data, $start_date,$end_date); 
		} 

		$data= $this->agentNotificationRepository->Pagination($data,$paginate); 

		$response = array(
			"count" => $data->count(), 
			"total" => $data->total(),
			"data" => $data
		);   
        // Log::info($response);
		return $response;  
	}
	

	public function savePostData($data)
    {
        try {
            $post = $this->agentNotificationRepository->save($data);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
	
	


}

