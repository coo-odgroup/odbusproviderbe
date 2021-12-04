<?php
namespace App\Services;

use App\Models\Location;
use App\Repositories\TicketFareSlabRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TicketFareSlabService

{
    /**
     * @var $postRepository
     */
    protected $ticketFareSlabRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(TicketFareSlabRepository $ticketFareSlabRepository)
    {
        $this->ticketFareSlabRepository = $ticketFareSlabRepository;
    }

   
    public function createslab($data)
    {
        $result = $this->ticketFareSlabRepository->createslab($data);

        return $result;
       
    }
     public function ticketFareSlabData($data)
    {
        $result = $this->ticketFareSlabRepository->ticketFareSlabData($data);

        return $result;
       
    }

     public function changeStatusticketFareSlab($id)
    {
        $result = $this->ticketFareSlabRepository->changeStatusticketFareSlab($id);

        return $result;
       
    }
     public function deleteticketFareSlab($id)
    {
        $result = $this->ticketFareSlabRepository->deleteticketFareSlab($id);

        return $result;
       
    }

  
}