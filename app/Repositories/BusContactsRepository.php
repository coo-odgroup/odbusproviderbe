<?php

namespace App\Repositories;

use App\Models\BusContacts;

class BusContactsRepository
{
    
    protected $busContacts;

    
    public function __construct(BusContacts $busContacts)
    {
        $this->busContacts = $busContacts;
    }
    public function getAll()
    {
        return $this->busContacts->whereNotIn('status', [2])->get();
    }
    public function getById($id)
    {
        return $this->busContacts ->where('id', $id)->get();
    }
    public function getByBusId($id)
    {
        return $this->busContacts ->where('bus_id', $id)->get();
    }
    public function getModel($data, BusContacts $busContacts)
    {
        $busContacts->bus_id = $data['bus_id'];
        $busContacts->type = $data['type'];
        $busContacts->phone = $data['phone'];
        $busContacts->booking_sms_send = $data['booking_sms_send'];
        $busContacts->cancel_sms_send = $data['cancel_sms_send'];
        $busContacts->created_by = $data['created_by'];
        return $busContacts;
    }
    public function save($data)
    {
        $busContacts = new $this->busContacts;
        $busContacts=$this->getModel($data, $busContacts);
        $busContacts->save();
        return $busContacts;
    }
    public function update($data, $id)
    {
        $busContacts = $this->busContacts->find($id);
        $busContacts=$this->getModel($data, $busContacts);
        $busContacts->update();
        return $busContacts;
    }
    public function delete($id)
    {
        $busContacts = $this->busContacts->find($id);
        $busContacts->status = 2;
        $busContacts->update();
        return $busContacts;
    }
    public function deletebyBusid($id)
    {
        $busContacts = $this->busContacts->where("bus_id",$id);
        $busContacts->delete();
        return $busContacts;
    }
}