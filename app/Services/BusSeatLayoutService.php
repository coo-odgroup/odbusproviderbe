<?php

namespace App\Services;

use App\Models\BusSeatLayout;
use App\Repositories\BusSeatLayoutRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusSeatLayoutService
{
    /**
     * @var $busSeatLayoutRepository
     */
    protected $busSeatLayoutRepository;

    /**
     * BusSeatLayoutService constructor.
     *
     * @param BusSeatLayoutRepository $busSeatLayoutRepository
     */
    public function __construct(BusSeatLayoutRepository $busSeatLayoutRepository)
    {
        $this->busSeatLayoutRepository = $busSeatLayoutRepository;
    }
    /**
     * Delete  by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $post = $this->busSeatLayoutRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException($e->getMessage());
        }
        return $post;

    }

    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busSeatLayoutRepository->getAll();
    }
    public function BusSeatLayoutOperator($request)
    {
        return $this->busSeatLayoutRepository->BusSeatLayoutOperator($request);
    }
    public function BusSeatLayoutbyUser($request)
    {
        return $this->busSeatLayoutRepository->BusSeatLayoutbyUser($request);
    }
    

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getRowCol($id,$type)
    {
        return $this->busSeatLayoutRepository->getRowCol($id,$type);
    }
    public function getById($id)
    {
        return $this->busSeatLayoutRepository->getById($id);
    }

    public function getSeatLayoutRecord($id)
    {
        return $this->busSeatLayoutRepository->getSeatLayoutRecord($id);
    }

    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update($data, $id)
    {
        try {
            $post = $this->busSeatLayoutRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return $post;

    }
    
    
    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save($data)
    {
        $result = $this->busSeatLayoutRepository->save($data);
        return $result;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getAllBusSeatLayoutDT($request)
    {
        return $this->busSeatLayoutRepository->getAllBusSeatLayoutDT($request);
    } 

    public function BusSeatLayoutData($request)
    {
        return $this->busSeatLayoutRepository->BusSeatLayoutData($request);
    }

    
    public function changeStatus($id)
    {
        try {
            $post = $this->busSeatLayoutRepository->changeStatus($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $post;

    }

}