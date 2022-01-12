<?php
namespace App\Services;
use App\Models\Location;
use App\Repositories\TicketInformationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TicketInformationService
{
    /**
     * @var $postRepository
     */
    protected $ticketInformationRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(TicketInformationRepository $ticketInformationRepository)
    {
        $this->ticketInformationRepository = $ticketInformationRepository;
    }


    
    public function getpnrdetails($request)
    {
        return $this->ticketInformationRepository->getpnrdetails($request);
    } 

    public function cancelticket($request)
    {
        return $this->ticketInformationRepository->cancelticket($request);
    } 

    public function cancelticketdata($request)
    {
        return $this->ticketInformationRepository->cancelticketdata($request);
    }

   
   
    
}