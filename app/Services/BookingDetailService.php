<?php

namespace App\Services;

use App\Models\BookingDetail;
use App\Repositories\BookingDetailRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BookingDetailService
{
    
    protected $bookingDetailRepository;

    
    public function __construct(BookingDetailRepository $bookingDetailRepository)
    {
        $this->bookingDetailRepository = $bookingDetailRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->bookingDetailRepository->delete($id);

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
        return $this->bookingDetailRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->bookingDetailRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        
        DB::beginTransaction();

        try {
            $post = $this->bookingDetailRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update ');
        }

        DB::commit();

        return $post;

    }

    
    public function savePostData($data)
    {
        
        $result = $this->bookingDetailRepository->save($data);

        return $result;
    }

}