<?php

namespace App\Services;

use App\Models\PreBooking;
use App\Models\Bus;
use App\Repositories\PreBookingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PreBookingService
{
    
    protected $preBookingRepository;

    
    public function __construct(PreBookingRepository $preBookingRepository)
    {
        $this->preBookingRepository = $preBookingRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->preBookingRepository->delete($id);

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
        return $this->preBookingRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->preBookingRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $post = $this->preBookingRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

    }

    
    public function savePreBookingData($data)
    {
        
        $result = $this->preBookingRepository->savePreBooking($data);

        return $result;
    }

    
    public function savePreBooking($data)
    {
       
        $result = $this->preBookingRepository->savePreBookingPhaseOne($data);

        return $result;
    }

    public function updatePreBookingPhaseTwo($data, $transaction_id)
    {
        // print_r($data);exit;
 
        DB::beginTransaction();

        try {
            $post = $this->preBookingRepository->updatePreBookingTwo($data, $transaction_id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update');
        }

        DB::commit();

        return $post;

    }

}