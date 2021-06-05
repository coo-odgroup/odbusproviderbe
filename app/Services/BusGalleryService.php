<?php

namespace App\Services;

use App\Models\BusGallery;
use App\Repositories\BusGalleryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusGalleryService
{
    /**
     * @var $busGalleryRepository
     */
    protected $busGalleryRepository;

    /**
     * BusGalleryService constructor.
     *
     * @param BusGalleryRepository $busGalleryRepository
     */
    public function __construct(BusGalleryRepository $busGalleryRepository)
    {
        $this->busGalleryRepository = $busGalleryRepository;
    }

    /**
     * Delete  by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->busGalleryRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->busGalleryRepository->getAll();
    }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->busGalleryRepository->getById($id);
    }

    public function getByBusId($bid)
    {
        return $this->busGalleryRepository->getByBusId($bid);
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
        

        DB::beginTransaction();

        try {
            $post = $this->busGalleryRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

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
        

        $result = $this->busGalleryRepository->save($data);

        return $result;
    }

}