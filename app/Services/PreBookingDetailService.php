<?php

namespace App\Services;

use App\Models\PreBookingDetail;
use App\Repositories\PreBookingDetailRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PreBookingDetailService
{
    
    protected $preBookingDetailRepository;

    
    public function __construct(PreBookingDetailRepository $preBookingDetailRepository)
    {
        $this->preBookingDetailRepository = $preBookingDetailRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->preBookingDetailRepository->delete($id);

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
    //     return $this->preBookingDetailRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->preBookingDetailRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->preBookingDetailRepository->update($data, $id);

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
        
    //     $result = $this->preBookingDetailRepository->save($data);

    //     return $result;
    // }

}