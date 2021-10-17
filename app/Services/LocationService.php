<?php

namespace App\Services;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class LocationService
{
    /**
     * @var $postRepository
     */
    protected $locationRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(LocationRepository $locationRepository)
    {
        $this->LocationRepository = $locationRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $post = $this->LocationRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }

    /**
     * Get all post.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->LocationRepository->getAll();
    }

   
    /**
     * Get post by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->LocationRepository->getById($id);
    }

    /**
     * Update post data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateLocation($data, $id)
    {
        /*$validator = Validator::make($data, [
            // 'type' => 'bail|min:2',
            'name' => 'bail|max:50'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }*/

        try {
            $location = $this->LocationRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }

        return $location;

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
        $result = $this->LocationRepository->save($data);
        return $result;
    }

    public function addPostData($data)
    {

        $result = $this->LocationRepository->add($data);

        return $result;
       
    }

    public function editPost($data, $id)
    {
        

        try {
            $post = $this->LocationRepository->edit($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }

        return $post;

    }


    public function datafilter($request)
    {
        return $this->LocationRepository->filter($request);
    }
    public function search($search)
    {
        return $this->LocationRepository->search($search);
    }

    public function getAllLocationDT($request)
    {
        return $this->LocationRepository->getAllLocationDT($request);
    } 


    public function locationsData($request)
    {
        return $this->LocationRepository->locationsData($request);
    }


    public function changeStatus($id)
    {
        try {
            $post = $this->LocationRepository->changeStatus($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $post;

    }
}