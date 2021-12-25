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


    
    
    public function getAll()
    {
        return $this->bookingseizedRepository->getAll();
    }

    public function bookingseizedById($id)
    {
        return $this->bookingseizedRepository->bookingseizedById($id);
    }


    
    public function updateSeized($data)
    { 
        try {
            $seized = $this->bookingseizedRepository->update($data);
        } catch (Exception $e) {
            // Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $seized;
    }


    public function bookingseizedData($data)
    { 

       return $seized = $this->bookingseizedRepository->bookingseizedData($data);
   }  

   public function deletebookingseized($id)
    { 

       return $seized = $this->bookingseizedRepository->deletebookingseized($id);
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


public function saveSeized($data)
{   
    return  $seized = $this->bookingseizedRepository->save($data);
}

}