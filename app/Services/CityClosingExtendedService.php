<?php

namespace App\Services;

use App\Models\CityClosingExtended;
use App\Repositories\CityClosingExtendedRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class CityClosingExtendedService
{
    
    protected $cityClosingExtendedRepository;

    
    public function __construct(cityClosingExtendedRepository $cityClosingExtendedRepository)
    {
        $this->cityClosingExtendedRepository = $cityClosingExtendedRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->cityClosingExtendedRepository->delete($id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to delete post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    
    // public function getAll()
    // {
    //     return $this->cityClosingExtendedRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->cityClosingExtendedRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->cityClosingExtendedRepository->update($data, $id);

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
        

    //     $result = $this->cityClosingExtendedRepository->save($data);

    //     return $result;
    // }

}