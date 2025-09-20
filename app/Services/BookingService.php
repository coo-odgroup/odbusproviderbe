<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BookingService
{
    
    protected $bookingRepository;

    
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->bookingRepository->delete($id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         // Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to delete ');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    
    // public function getAll()
    // {
    //     return $this->bookingRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->bookingRepository->getById($id);
    // }

   
//     public function updatePost($data, $id)
//     {
        

//         DB::beginTransaction();

//         try {
//             $post = $this->bookingRepository->update($data, $id);

//         } catch (Exception $e) {
//             DB::rollBack();
//             // Log::info($e->getMessage());

//             throw new InvalidArgumentException('Unable to update post data');
//         }

//         DB::commit();

//         return $post;

//     }

    
    // public function saveBookingData($data)
    // {
        
    //     $result = $this->bookingRepository->saveBooking($data);

    //     return $result;
    // }

}