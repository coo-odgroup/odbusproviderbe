<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\ApiUserManageOperator;
use App\Models\ApiUserManageOperatorDatewise;
use Illuminate\Support\Facades\DB;
class ApiUserManageOperatorRepository
{
    /**
     * @var Agent
     */
    protected $ApiUserManageOperator;

    /**
     * AgentRepository constructor.
     *
     * @param Post $agent
     */
    public function __construct(ApiUserManageOperator $ApiUserManageOperator)
    {
        $this->ApiUserManageOperator = $ApiUserManageOperator;
    }


    public function manageClientOperatorData($request)
    {

        $paginate = $request['rows_number'] ;
        $user = $request['user_id'] ;


        $data = $this->ApiUserManageOperator->with('user', 'busOperator')->orderBy('updated_at', 'DESC');

        if ($paginate == 'all') {
            $paginate = Config::get('constants.ALL_RECORDS');
        } elseif ($paginate == null) {
            $paginate = 10 ;
        } elseif ($user != null) {
            $data = $data->where('user_id', $user);
        }

        $data = $data->paginate($paginate);

        // log::info($data);

        $response = array(
             "count" => $data->count(),
             "total" => $data->total(),
            "data" => $data
           );

        return $response;


    }



    public function manageClientOperator_old($data)
    {
        $insertData = [];

        if ($data != '') {
            foreach ($data['bus_operator_id'] as $e => $k) {
                $apiOperator = new $this->ApiUserManageOperator();
                $apiOperator->user_id = $data['user_id'];
                $apiOperator->bus_operator_id = $k;
                $apiOperator->created_by = $data->created_by;
                $apiOperator->save();
            }
            return $apiOperator;
        }
    }

    public function manageClientOperator($data)
    {
        foreach ($data->bus_operator_id as $operatorId) {

            if ($data->restriction_type == 'permanent') {

                $exists = ApiUserManageOperator::where('user_id', $data->user_id)
                    ->where('bus_operator_id', $operatorId)
                    ->where('restriction_type', 'permanent')
                    ->exists();

                if (!$exists) {

                    $apiOperator = new $this->ApiUserManageOperator();
                    $apiOperator->user_id = $data->user_id;
                    $apiOperator->bus_operator_id = $operatorId;
                    $apiOperator->restriction_type = 'permanent';
                    $apiOperator->journey_date = null;
                    $apiOperator->created_by = $data->created_by;
                    $apiOperator->save();
                }

            } else {

                foreach ($data->journey_dates as $date) {

                    $journeyDate =
                        $date['year'] . '-' .
                        str_pad($date['month'], 2, '0', STR_PAD_LEFT) . '-' .
                        str_pad($date['day'], 2, '0', STR_PAD_LEFT);

                    $exists = ApiUserManageOperator::where('user_id', $data->user_id)
                        ->where('bus_operator_id', $operatorId)
                        ->where('restriction_type', 'datewise')
                        ->where('journey_date', $journeyDate)
                        ->exists();

                    if (!$exists) {

                        $apiOperator = new $this->ApiUserManageOperator();
                        $apiOperator->user_id = $data->user_id;
                        $apiOperator->bus_operator_id = $operatorId;
                        $apiOperator->restriction_type = 'datewise';
                        $apiOperator->journey_date = $journeyDate;
                        $apiOperator->created_by = $data->created_by;
                        $apiOperator->save();
                    }
                }
            }
        }

        return true;

    }



    public function deletemanageClientOperator($id)
    {
        // log::info($id);


        $post = $this->ApiUserManageOperator->find($id);
        $post->delete();


        return $post;

    }

}
