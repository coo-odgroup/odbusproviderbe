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

    public function getPnrDetailsForSms($request)
    {
        return $this->ticketInformationRepository->getPnrDetailsForSms($request);
    } 

    public function cancelticket($request)
    {
        return $this->ticketInformationRepository->cancelticket($request);
    } 

    public function cancelticketdata($request)
    {
        return $this->ticketInformationRepository->cancelticketdata($request);
    }
    public function adjustticketdata ($request)
    {
        return $this->ticketInformationRepository->adjustticketdata ($request);
    }

    public function adjustticket($request)
    {
        return $this->ticketInformationRepository->adjustticket($request);
    }

    public function getDetailsSms($request)
    {
        return $this->ticketInformationRepository->getDetailsSms($request);
    } 

    public function getBookingID($request)
    {
        return $this->ticketInformationRepository->getBookingID($request);
    }  

    public function save_customSMS($request)
    {
        return $this->ticketInformationRepository->save_customSMS($request);
    } 

    public function GetCancelSmsToCustomer($request)
    {
        return $this->ticketInformationRepository->GetCancelSmsToCustomer($request);
    }

    public function GetCancelSmsToCMO($request)
    {
        return $this->ticketInformationRepository->GetCancelSmsToCMO($request);
    }

    public function save_CancelcustomSMSToCustomer($request)
    {
        return $this->ticketInformationRepository->save_CancelcustomSMSToCustomer($request);
    } 

    public function save_CancelcustomSMSToCMO($request)
    {
        return $this->ticketInformationRepository->save_CancelcustomSMSToCMO($request);
    } 

    public function getEmailID($request)
    {
        return $this->ticketInformationRepository->getEmailID($request);
    }
}