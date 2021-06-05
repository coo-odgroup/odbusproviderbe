<?php

namespace App\Services;

use App\Models\BusStoppageAdditionalFare;
use App\Repositories\BusStoppageAdditionalFareRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusStoppageAdditionalFareService
{
    
    protected $busStoppageAdditionalFareRepository;

    
    public function __construct(busStoppageAdditionalFareRepository $busStoppageAdditionalFareRepository)
    {
        $this->busStoppageAdditionalFareRepository = $busStoppageAdditionalFareRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->busStoppageAdditionalFareRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    
    public function getAll()
    {
        return $this->busStoppageAdditionalFareRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->busStoppageAdditionalFareRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $post = $this->busStoppageAdditionalFareRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

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
        

        $result = $this->busStoppageAdditionalFareRepository->save($data);

        return $result;
    }

}