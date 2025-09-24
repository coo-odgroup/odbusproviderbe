<?php

namespace App\Services;

use App\Models\CustomerQuery;
use App\Repositories\CustomerQueryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CustomerQueryService
{
    /**
     * @var $customerQueryRepository
     */
    protected $customerQueryRepository;

    /**
     * CustomerQueryService constructor.
     *
     * @param CustomerQueryRepository $customerQueryRepository
     */
    public function __construct(CustomerQueryRepository $customerQueryRepository)
    {
        $this->customerQueryRepository = $customerQueryRepository;
    }

    /**
     * Delete  by id.
     *
     * @param $id
     * @return String
     */
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->customerQueryRepository->delete($id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to delete post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    /**
     * Get all Data.
     *
     * @return String
     */
    // public function getAll()
    // {
    //     return $this->customerQueryRepository->getAll();
    // }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    // public function getById($id)
    // {
    //     return $this->customerQueryRepository->getById($id);
    // }

    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->customerQueryRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to update post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function savePostData($data)
    // {
        

    //     $result = $this->customerQueryRepository->save($data);

    //     return $result;
    // }

}