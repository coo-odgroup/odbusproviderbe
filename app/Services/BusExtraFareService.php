<?php

namespace App\Services;

use App\Models\BusExtraFare;
use App\Repositories\BusExtraFareRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusExtraFareService
{
    
    protected $busExtraFareRepository;

    
    public function __construct(busExtraFareRepository $busExtraFareRepository)
    {
        $this->busExtraFareRepository = $busExtraFareRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->busExtraFareRepository->delete($id);

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
        return $this->busExtraFareRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->busExtraFareRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $post = $this->busExtraFareRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

    }

    
    public function savePostData($data)
    {
        

        $result = $this->busExtraFareRepository->save($data);

        return $result;
    }

}