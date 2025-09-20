<?php

namespace App\Services;

use App\Models\BusSlots;
use App\Repositories\BusSlotsRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusSlotsService
{
    
    protected $BusSlotsRepository;

    
    public function __construct(BusSlotsRepository $BusSlotsRepository)
    {
        $this->BusSlotsRepository = $BusSlotsRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->BusSlotsRepository->delete($id);

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
    //     return $this->BusSlotsRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->BusSlotsRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->BusSlotsRepository->update($data, $id);

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
        

    //     $result = $this->BusSlotsRepository->save($data);

    //     return $result;
    // }

}