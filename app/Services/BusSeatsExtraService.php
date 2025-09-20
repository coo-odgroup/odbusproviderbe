<?php

namespace App\Services;

use App\Models\BusSeatsExtra;
use App\Repositories\BusSeatsExtraRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use InvalidArgumentException;

class BusSeatsExtraService
{
    
    protected $busSeatsExtraRepository;

    
    public function __construct(busSeatsExtraRepository $busSeatsExtraRepository)
    {
        $this->busSeatsExtraRepository = $busSeatsExtraRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->busSeatsExtraRepository->delete($id);

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
    //     return $this->busSeatsExtraRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->busSeatsExtraRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->busSeatsExtraRepository->update($data, $id);

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
        

    //     $result = $this->busSeatsExtraRepository->save($data);

    //     return $result;
    // }

}