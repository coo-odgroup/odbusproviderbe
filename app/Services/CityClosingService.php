<?php

namespace App\Services;

use App\Models\CityClosing;
use App\Repositories\CityClosingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CityClosingService
{
    
    protected $cityClosingRepository;

    
    public function __construct(cityClosingRepository $cityClosingRepository)
    {
        $this->cityClosingRepository = $cityClosingRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->cityClosingRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    
    // public function getAll()
    // {
    //     return $this->cityClosingRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->cityClosingRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->cityClosingRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to update post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    
    // public function savePostData($data)
    // {
        

    //     $result = $this->cityClosingRepository->save($data);

    //     return $result;
    // }

}