<?php

namespace App\Repositories;
use App\Models\OdbusCharges;
use App\Models\BusOperator;
use Illuminate\Support\Facades\Log;
class OdbusChargesRepository 
{
    protected $odbusCharges;
    public function __construct(OdbusCharges $odbusCharges)
    {
        $this->odbusCharges = $odbusCharges;
    }
    public function getData($request)
    {
        $paginate = $request['per_page'] ;
        $name = $request['name'] ;

        $data= OdbusCharges::with('busOperator')->whereNotIn('status', [2])->orderBy('id','desc');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
        if($name!=null)
        {
            $data = $data->WhereHas('busOperator', function ($query) use ($name) {$query->where('operator_name', 'like', '%' .$name . '%');})
                        ->orWhereHas('busOperator', function ($query) use ($name) {$query->where('contact_number', 'like', '%' .$name . '%');})
                        ->orWhereHas('busOperator', function ($query) use ($name) {$query->where('email_id', 'like', '%' .$name . '%');});                       
        }     
        $data=$data->paginate($paginate);
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "data" => $data
           );   
           return $response; 
    }

    public function getAll()
    {
        return $this->odbusCharges->whereNotIn('status', [2])->get();
    }
    
    public function getById($id)
    {
        return $this->odbusCharges
            ->where('id', $id)
            ->get();
    }
    
    public function getModel($data, OdbusCharges $odbusCharges)
    {
        $odbusCharges->bus_operator_id = $data['bus_operator_id'];
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
        $odbusCharges->favIcon = $data['favIcon'];
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
        $odbusCharges = $this->odbusCharges->find($id);
        $odbusCharges=$this->getModel($data,$odbusCharges);
        $odbusCharges->update();
        return $odbusCharges;
    }
    public function delete($id)
    {
        $charges = $this->odbusCharges->find($id);
        $charges->status = 2;
        $charges->update();

        return $charges;
    }
    public function changeStatus($id)
    {
        $charges = $this->odbusCharges->find($id);
        if($charges->status==0){
            $charges->status = 1;
        }elseif($charges->status==1){
            $charges->status = 0;
        }
        $charges->update();
        return $charges;
    }


}