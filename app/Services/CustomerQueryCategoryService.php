<?php

namespace App\Services;

use App\Models\CustomerQueryCategory;
use App\Repositories\CustomerQueryCategoryRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CustomerQueryCategoryService
{
    /**
     * @var $postRepository
     */
    protected $customerQueryCategoryRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(CustomerQueryCategoryRepository $customerQueryCategoryRepository)
    {
        $this->customerQueryCategoryRepository = $customerQueryCategoryRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->customerQueryCategoryRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->customerQueryCategoryRepository->getAll();
    }

    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->customerQueryCategoryRepository->getById($id);
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateCustomerQueryCategory($data, $id)
    {
       
        DB::beginTransaction();

        try {
            $customerQueryCategory = $this->customerQueryCategoryRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $customerQueryCategory;

    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {   
        $result = $this->customerQueryCategoryRepository->save($data);
        return $result;
    }
   

}