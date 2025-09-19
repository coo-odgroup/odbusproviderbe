<?php

namespace App\Services;

use App\Models\TicketCancelation;
use App\Repositories\TicketCancelationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TicketCancelationService
{
    
    protected $ticketCancelationRepository;

    
    public function __construct(TicketCancelationRepository $ticketCancelationRepository)
    {
        $this->TicketCancelationRepository = $ticketCancelationRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->TicketCancelationRepository->delete($id);

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
    //     return $this->TicketCancelationRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->TicketCancelationRepository->getById($id);
    // }

    
     
    public function updateLocation($data, $id)
    {
        

        DB::beginTransaction();

        try {
            $ticket = $this->TicketCancelationRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $ticket;

    }

    
    // public function savePostData($data)
    // {   
    //     $result = $this->TicketCancelationRepository->save($data);
    //     return $result;
    // }


    

}