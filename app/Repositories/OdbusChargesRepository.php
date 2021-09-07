<?php

namespace App\Repositories;
use App\Models\OdbusCharges;
use Illuminate\Support\Facades\Log;
class OdbusChargesRepository 
{
    protected $odbusCharges;
    public function __construct(OdbusCharges $odbusCharges)
    {
        $this->odbusCharges = $odbusCharges;
    }
    public function getAll()
    {
        return $this->safety->whereNotIn('status', [2])->get();
    }
    
    public function getById($id)
    {
        return $this->odbusCharges
            ->where('id', $id)
            ->get();
    }
    
    public function getModel($data, OdbusCharges $odbusCharges)
    {
        Log::info($data);
        $odbusCharges->payment_gateway_charges = $data['payment_gateway_charges'];
        $odbusCharges->email_sms_charges = $data['email_sms_charges'];
        $odbusCharges->odbus_gst_charges = $data['odbus_gst_charges'];
        $odbusCharges->created_by = $data['created_by'];
        return $odbusCharges;
    }
    /**
     * Save Settings
     *
     * @param $data
     * @return Settings
     */
    public function save($data)
    {
        $odbusCharges = new $this->odbusCharges;
        $odbusCharges=$this->getModel($data,$odbusCharges);
        $odbusCharges->save();
        return $odbusCharges;
    }
    /**
     * Update Settings
     *
     * @param $data
     * @return Settings
     */
    public function update($data, $id)
    {
        Log::info($data);
        $odbusCharges = $this->odbusCharges->find($id);
        $odbusCharges=$this->getModel($data,$odbusCharges);
        $odbusCharges->update();
        return $odbusCharges;
    }



}