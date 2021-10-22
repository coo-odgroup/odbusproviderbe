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
        $odbusCharges->payment_gateway_charges = $data['payment_gateway_charges'];
        $odbusCharges->email_sms_charges = $data['email_sms_charges'];
        $odbusCharges->odbus_gst_charges = $data['odbus_gst_charges'];
        $odbusCharges->advance_days_show = $data['advance_days_show'];
        $odbusCharges->support_email = $data['support_email'];
        $odbusCharges->booking_email = $data['booking_email'];
        $odbusCharges->request_email = $data['request_email'];
        $odbusCharges->other_email = $data['other_email'];
        $odbusCharges->mobile_no_1 = $data['mobile_no_1'];
        $odbusCharges->mobile_no_2 = $data['mobile_no_2'];
        $odbusCharges->mobile_no_3 = $data['mobile_no_3'];
        $odbusCharges->mobile_no_4 = $data['mobile_no_4'];
        $odbusCharges->logo = $data['logo'];
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
        // Log::info($data);
        $odbusCharges = $this->odbusCharges->find($id);
        $odbusCharges=$this->getModel($data,$odbusCharges);
        $odbusCharges->update();
        return $odbusCharges;
    }



}