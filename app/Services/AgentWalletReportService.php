<?php

namespace App\Services;


use App\Repositories\AgentWalletReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class AgentWalletReportService
{
	protected $agentWalletReportRepository;

	public function __construct(AgentWalletReportRepository $agentWalletReportRepository)
	{
		$this->agentWalletReportRepository = $agentWalletReportRepository;
	}

	public function getalldata($request)
	{
		// Log:info($request);
		$paginate = $request['rows_number'] ;
		$name = $request['name'] ;
		$user_id =$request['user_id'];


		$data= $this->agentWalletReportRepository->getWalletRecord($user_id);

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
			$data = $this->agentWalletReportRepository->Filter($data, $name);                     
		}     

		$data= $this->agentWalletReportRepository->Pagination($data,$paginate); 

		$response = array(
			"count" => $data->count(), 
			"total" => $data->total(),
			"data" => $data
		);  

		return $response;  

	}


}