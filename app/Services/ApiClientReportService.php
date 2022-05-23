<?php
namespace App\Services;

use App\Models\BusOwnerFare;
use App\Repositories\ApiClientReportRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiClientReportService
{
    
    protected $ApiClientReportRepository;

    
    public function __construct(ApiClientReportRepository $ApiClientReportRepository)
    {
        $this->ApiClientReportRepository = $ApiClientReportRepository;
    } 

   
    public function getAllData($request)
    {
      return $this->ApiClientReportRepository->getAllData($request);
    }

    public function getAllCancelData($request)
    {
      return $this->ApiClientReportRepository->getAllCancelData($request);
    }
	
	public function datewiseroute($request)
    {
      return $this->ApiClientReportRepository->datewiseroute($request);
    }

}

