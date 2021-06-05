<?php

namespace App\Repositories;

use App\Models\TicketCancelation;
use App\Models\TicketCancelationRule;

class TicketCancelationRepository
{
    
    protected $ticketCancelation;
    protected $ticketCancelationRule;

    
    public function __construct(TicketCancelation $ticketCancelation, TicketCancelationRule $ticketCancelationRule)
    {
        $this->TicketCancelation = $ticketCancelation;
        $this->TicketCancelationRule = $ticketCancelationRule;
    }

    
    public function getAll()
    {
         
        return $this->TicketCancelation::with('TicketCancelationRule')->get();
    }

    
    public function getById($id)
    {
        return $this->TicketCancelation::with('TicketCancelationRule')->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $ticketCancelation = new $this->TicketCancelation;
        $ticketCancelation->name = $data['name'];
        $ticketCancelation->created_by= $data['created_by'];
        $ticketCancelation->save();

        foreach($data['ticketCancelationRule'] as $ticket){
          $ticketdetRecord = new $this->TicketCancelationRule;   
          $ticketdetRecord->ticket_cancelation_id = $ticketCancelation->id;     
          $ticketdetRecord->hour_lag_start =  $ticket['hour_lag_start'];
          $ticketdetRecord->hour_lag_end =  $ticket['hour_lag_end'];
          $ticketdetRecord->cancelation_percentage =  $ticket['cancelation_percentage'];
          $ticketdetRecord->created_by =  $ticket['created_by'];
          $ticketCancelation->ticketCancelationRule[] = $ticketdetRecord;
            
        }   
        $ticketCancelation->push();
        return $ticketCancelation->fresh();
    }
    



}