<?php

namespace App\Services;

use App\Repositories\BusLocationSequenceRepository;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;
class BusLocationSequenceService
{
    /**
     * @var $busGalleryRepository
     */
    protected $busLocationSequenceRepository;

    /**
     * BusLocationSequenceRepository constructor.
     *
     * @param BusLocationSequenceRepository $busLocationSequenceService
     */
    public function __construct(BusLocationSequenceRepository $busLocationSequenceService)
    {
        $this->busLocationSequenceRepository = $busLocationSequenceRepository;
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
            $post = $this->busLocationSequenceRepository->delete($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
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
        return $this->busLocationSequenceRepository->getAll();
    }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busLocationSequenceRepository->getById($id);
    }

    public function getByBusId($bid)
    {
        return $this->busLocationSequenceRepository->getByBusId($bid);
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
            $post = $this->busLocationSequenceRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
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
    public function savePostData($data)
    {
        $result = $this->busLocationSequenceRepository->save($data);
        return $result;
    }
}