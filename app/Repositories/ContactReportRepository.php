<?php

namespace App\Repositories;


use App\Models\Contact;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class ContactReportRepository
{
    protected $contact;
   
    public function __construct(Contact $contact)
    {              
        $this->contact = $contact;       
    }    
    public function getAll()
    {
        $data = $this->contact->where('status', 1)->orderBy('id',"DESC")->get() ;
       
        return $data;     
    }


    public function getData($request)
    {
        $response = "Post Working Fine";
           return $response;      

    } 

    public function deleteData($id)
    {
        $contact = $this->contact->find($id);
        $contact->status = 2;
        $contact->update();

        return $contact;      

    }
    
}

