<?php

namespace App\Services;


use App\Repositories\BookingSeizedRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;


class BookingSeizedService
{
    protected $bookingseizedRepository;

    
   
    public function __construct(BookingSeizedRepository $bookingseizedRepository)
    {
        $this->bookingseizedRepository = $bookingseizedRepository;
    }

   
    // public function deleteById($id)
    // {
    //     try {
    //         $seatopen = $this->seatopenRepository->delete($id);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $seatopen;
    // }

   
    
    public function getAll()
    {
        return $this->bookingseizedRepository->getAll();
    }

    // public function addseatopen($request)
    // {
    //     return $this->seatopenRepository->addseatopen($request);
    // } 
    // public function updateseatopen($request, $id)
    // {
    //     try {
    //         $seatopen = $this->seatopenRepository->updateseatopen($request, $id);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $seatopen;
    // }
    
    // public function getseatopenDT($request)
    // {
    //     return $this->seatopenRepository->getseatopenDT($request);
    // }
   
    
    // public function getById($id)
    // {
    //     return $this->bookingseizedRepository->getById($id);
    // }
    
    public function updateSeized($data)
    { 
        try {
            $seized = $this->bookingseizedRepository->update($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $seized;
    }



    public function changeStatus($id)
    {
        try {
            $post = $this->bookingseizedRepository->changeStatus($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $post;
    }

   
    // public function savePostData($data)
    // {   
    //     try {
    //         $amenity = $this->seatopenRepository->save($data);
    //     } catch (Exception $e) {
    //         Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //     }
    //     return $amenity;
    // }

}