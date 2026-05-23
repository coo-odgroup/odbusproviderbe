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
        $paginate   = $request['rows_number'];
        $name       = $request['name'];
        $user_id    = $request['user_id'];
        $tran_type  = $request['tran_type'];
        $select_type = $request['SelectType'];
        $start_date = $request['from_date'];
        $end_date   = $request['to_date'];

        $data = $this->agentWalletReportRepository
            ->getWalletRecord($user_id);

        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif (empty($paginate)) {
            $paginate = 10;
        }

        if (!empty($name)) {
            $data = $this->agentWalletReportRepository
                ->Filter($data, $name);
        }

        if (!empty($tran_type)) {
            $data = $this->agentWalletReportRepository
                ->tranType($data, $tran_type);
        }

        if (!empty($select_type)) {
            $data = $this->agentWalletReportRepository
                ->selectType($data, $select_type);
        }

        // NEW DATE FILTER
        if (!empty($start_date) && !empty($end_date)) {
            $data = $this->agentWalletReportRepository
                ->FilterDate(
                    $data,
                    $start_date,
                    $end_date
                );
        }

        $data = $this->agentWalletReportRepository
            ->Pagination($data, $paginate);

        return [
            "count" => $data->count(),
            "total" => $data->total(),
            "data" => $data
        ];
    }
}
