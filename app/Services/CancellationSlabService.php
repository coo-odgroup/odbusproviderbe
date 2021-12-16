<?php
namespace App\Services;
use App\Repositories\CancellationSlabRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Exception;
use InvalidArgumentException;

class CancellationSlabService
{
    protected $cancellationSlabRepository;
    public function __construct(CancellationSlabRepository $cancellationSlabRepository)
    {
        $this->cancellationSlabRepository = $cancellationSlabRepository; 
    }
    /**
     * Delete Data by ID.
     *
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $cSlab = $this->cancellationSlabRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $cSlab;
    }
    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll($request)
    {
        return $this->cancellationSlabRepository->getAll($request);
    }
    public function cancellationslabsOperator($request)
    {
        return $this->cancellationSlabRepository->cancellationslabsOperator($request);
    }

     /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    public function getCancellationSlabDT($request)
    {
        return $this->cancellationSlabRepository->getCancellationSlabDT($request);
    } 

    public function cancellationslabData($request)
    {
        return $this->cancellationSlabRepository->cancellationslabData($request);
    }
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->cancellationSlabRepository->getById($id);
    }
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
            $cSlab = $this->cancellationSlabRepository->update($data, $id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $cSlab;
    }
    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {
        try {
            $cSlab = $this->cancellationSlabRepository->save($data);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $cSlab;
    }

    public function changeStatus($id)
    {
        try {
            $cSlab = $this->cancellationSlabRepository->changeStatus($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $cSlab;

    }
}