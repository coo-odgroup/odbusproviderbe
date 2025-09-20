<?php

namespace App\Services;

use App\Models\BusSchedule;
use App\Repositories\BusScheduleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusScheduleService
{
    protected $busScheduleRepository;
    public function __construct(busScheduleRepository $busScheduleRepository)
    {
        $this->busScheduleRepository = $busScheduleRepository;
    }
    /**
     * Delete Data by ID.
     *
     * @return String
     */
    // public function deleteById($id)
    // {
    //     try {
    //         $busSchedule = $this->busScheduleRepository->delete($id);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException('Unable to delete post data');
    //     }
    //     return $busSchedule;
    // }
    /**
     * Get all Data.
     *
     * @return String
     */
    // public function getAll()
    // {
    //     return $this->busScheduleRepository->getAll();
    // }

    // public function scheduleCronJob()
    // {
    //     return $this->busScheduleRepository->scheduleCronJob();
    // }

    // public function removeOldBusScheduleCronjob()
    // {
    //     return $this->busScheduleRepository->removeOldBusScheduleCronjob();
    // }

    // public function busScheduleById($id)
    // {
    //     return $this->busScheduleRepository->busScheduleById($id);
    // }
     /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    // public function dataTable($request)
    // {
    //     return $this->busScheduleRepository->getDatatable($request);
    // }

    //  public function busSchedulerData($request)
    // {
    //     return $this->busScheduleRepository->busSchedulerData($request);
    // }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    // public function getById($id)
    // {
    //     return $this->busScheduleRepository->getById($id);
    // }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
   
    public function updatePost($data, $id)
    {
        try {
            $busSchedule = $this->busScheduleRepository->update($data, $id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busSchedule;
    }
    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */

    // public function savePostData($data)
    // {
    //     try {
    //         $busSchedule = $this->busScheduleRepository->save($data);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //      }
    //     return $busSchedule;
    // }
    // public function changeStatus($id)
    // {
    //     try {
    //         $busSchedule = $this->busScheduleRepository->changeStatus($id);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException('Unable to change status');
    //     }
    //     return $busSchedule;
    // }

    // public function unscheduledbuslist()
    // {
    //     try {
    //         $busSchedule = $this->busScheduleRepository->unscheduledbuslist();
    //         return $busSchedule;
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.EXCEPTION_ERROR'));
    //     }
       
    // }
}