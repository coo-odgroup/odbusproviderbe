<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\CouponAssignedBus;
use App\Models\CouponRoute;
use App\Models\CouponOperator;
use App\Models\Location;
use App\Models\Bus;
use App\Models\CouponType;
use Illuminate\Support\Facades\Log;
class CouponRepository
{
    
    protected $coupon;
    protected $couponAssignedBus;
    protected $couponRoute;
    protected $couponOperator;
    protected $location;
    protected $CouponType;
    protected $bus;
    
    public function __construct(Coupon $coupon, CouponAssignedBus $couponAssignedBus, CouponRoute $couponRoute, CouponOperator $couponOperator,Location $location,CouponType $CouponType,Bus $bus)
    {
        $this->coupon = $coupon;
        $this->couponAssignedBus = $couponAssignedBus;
        $this->couponRoute = $couponRoute;
        $this->couponOperator = $couponOperator;
        $this->location = $location; 
        $this->CouponType = $CouponType; 
        $this->bus=$bus;
    }

    
    public function getAll()
    {
        return $this->coupon->with('couponType')->where('status','!=',2)->get();
    }

    public function getAllCouponType()
    {
        return $this->CouponType->where('status',1)->get();
    }

    
    public function getById($id)
    {
        return $this->coupon ->where('id', $id)->get();
    }

    public function saveCouponBus($data)
    {
        $couponBusData = new $this->couponAssignedBus;
        $couponBusData->coupon_id = $data['coupon_id'];
        $couponBusData->bus_id = $data['bus_id'];
        $couponBusData->created_by = $data['created_by'];
        $couponBusData->save();
        return $couponBusData->fresh();
    }

    public function saveCouponRoute($data)
    {
        $couponRouteData = new $this->couponRoute;
        $couponRouteData->coupon_id = $data['coupon_id'];
        $couponRouteData->source_id = $data['source_id'];
        $couponRouteData->destination_id = $data['destination_id'];
        $couponRouteData->created_by = $data['created_by'];
        $couponRouteData->save();
        return $couponRouteData->fresh();
    }

    public function saveCouponOperator($data)
    {
        $couponOperatorRecord = new $this->couponOperator;
        $couponOperatorRecord->coupon_id = $data['coupon_id'];
        $couponOperatorRecord->operator_id = $data['operator_id'];
        $couponOperatorRecord->created_by = $data['created_by'];
        $couponOperatorRecord->save();
        return $couponOperatorRecord->fresh();
    }



    public function save($data)
    {

        $batch_insert_array=[];
       
        if($data['bus_id']){
            foreach($data['bus_id'] as $b ){

                $coupons = new $this->coupon;              


                $coupons->coupon_type_id = $data['coupon_type'];
                $coupons->coupon_title = $data['coupon_title'];
                $coupons->coupon_code = strtoupper($data['coupon_code']);      
                $coupons->type = $data['coupon_discount_type'];
                $coupons->valid_by = $data['valid_by'];

                    if($data['coupon_type'] == 1)
                    {
                        $ar=explode('-',$b);  
                        $opr_id=$ar[0];                    
                        $bus_id=$ar[1]; 

                        $coupons->bus_operator_id = $opr_id;
                        $coupons->bus_id = $bus_id;
                    }
                    else if($data['coupon_type'] == 2)
                    {
                        $ar=explode('-',$b);  
                        $src_id=$ar[0];                     
                        $dest_id=$ar[1];                     
                        $bus_id=$ar[2]; 

                        $coupons->source_id =  $src_id;
                        $coupons->destination_id = $dest_id;
                        $coupons->bus_id = $bus_id;
                    }
                    else
                    { 
                        $ar=explode('-',$b);  
                        $opr_id=$ar[0];                     
                        $src_id=$ar[1];                     
                        $dest_id=$ar[2];                     
                        $bus_id=$ar[3];  

                        $coupons->bus_operator_id = $opr_id;
                        $coupons->source_id =  $src_id;
                        $coupons->destination_id = $dest_id;
                        $coupons->bus_id = $bus_id;
                    }

                if($data['coupon_discount_type']==1)
                {
                    $coupons->percentage = $data['percentage'];
                    $coupons->max_discount_price = $data['max_discount_price'];
                    $coupons->amount = 0;
                    $coupons->min_tran_amount = 0;
                   
                }
                else
                {
                    $coupons->amount = $data['amount'];
                    $coupons->min_tran_amount = $data['min_tran_amount'];
                    $coupons->percentage = 0;
                    $coupons->max_discount_price = 0;
                }
                
                
                $coupons->max_redeem = $data['max_redeem'];
                $coupons->from_date = $data['from_date'];
                $coupons->to_date = $data['to_date'];
                $coupons->short_desc = $data['short_description'];
                $coupons->full_desc = $data['full_description'];
                $coupons->created_by = $data['created_by'];
                $coupons->auto_apply = ($data['auto_apply']==true) ? 1 : 0;
                $coupons->status = 0;

                ////////// check duplicacy bus for same date range before insert

                //\DB::connection()->enableQueryLog();

                $from_date=$data['from_date'];
                $to_date=$data['to_date'];

                $chk= $this->coupon->where('bus_id',$bus_id)
                                    ->where(function($query) use ($from_date, $to_date){
                                        $query->whereBetween('from_date', [$from_date,$to_date])
                                            ->orWhereBetween('to_date',[$from_date,$to_date]);
                                    })->get();

                // $queries  = \DB::getQueryLog(); 
                // $last_query = end($queries);                              
                // Log::info($queries);
                // Log::info($chk);

                 if(count($chk) >0){

                    $getBus= $this->bus->where("id",$bus_id)->get();

                    $error['status'] ='exist';
                    $error['message'] = 'Coupon is already added for '.$getBus[0]->name.' bus between '.$data['from_date']." - ".$data['to_date'];

                      return $error;
                 } 
                 else{
                    array_push($batch_insert_array,$coupons);
                 }

            }

            if($batch_insert_array){
                foreach($batch_insert_array as $coupons){
                    $coupons->save();
                }
                
            }
        }

        return 'success';
    }



    
    public function update($data, $id)
    {
        $coupons = $this->coupon->find($id);
        // $coupons->coupon_type_id = $data['coupon_type'];
        // $coupons->coupon_title = $data['coupon_title'];
        // $coupons->coupon_code = strtoupper($data['coupon_code']);
        // $coupons->bus_operator_id = $data['bus_operator_id'];
        // $coupons->source_id = $data['source_id'];
        // $coupons->destination_id = $data['destination_id'];
        // $coupons->type = $data['coupon_discount_type'];
        // $coupons->valid_by = $data['valid_by'];
        // if($data['coupon_type'] == 1)
        // {
        //     $coupons->bus_operator_id = $data['bus_operator_id'];
        //     $coupons->source_id = null;
        //     $coupons->destination_id = null;
        // }
        // else if($data['coupon_type'] == 2)
        // {
        //     $coupons->bus_operator_id = null;
        //     $coupons->source_id = $data['source_id'];
        //     $coupons->destination_id = $data['destination_id'];
        // }
        // else
        // {
        //     $coupons->bus_operator_id = $data['bus_operator_id'];
        //     $coupons->source_id = $data['source_id'];
        //     $coupons->destination_id = $data['destination_id'];
        // }

        // if($data['coupon_discount_type']==1)
        // {
        //     $coupons->percentage = $data['percentage'];
        //     $coupons->max_discount_price = $data['max_discount_price'];
        //     $coupons->amount = 0;
        //     $coupons->min_tran_amount = 0;
           
        // }
        // else
        // {
        //     $coupons->amount = $data['amount'];
        //     $coupons->min_tran_amount = $data['min_tran_amount'];
        //     $coupons->percentage = 0;
        //     $coupons->max_discount_price = 0;
        // }

        // $coupons->max_redeem = $data['max_redeem'];
        // $coupons->from_date = $data['from_date'];
        // $coupons->to_date = $data['to_date'];
        $coupons->short_desc = $data['short_description'];
        $coupons->full_desc = $data['full_description'];
        $coupons->auto_apply =($data['auto_apply']==true) ? 1 : 0;
        $coupons->created_by = $data['created_by'];
        //$coupons->status = 1;        

        $coupons->update();

        return $coupons;
    }

    public function getData($request)
    {
        // Log:: info($request);
        $name=$request->name;
        $paginate = $request->rows_number;
        $data= $this->coupon->with("BusOperator","couponType","Bus")->where('status','!=',2)->orderBy('id','DESC');
        if($request['USER_BUS_OPERATOR_ID']!="")
        {
            $data=$data->where('bus_operator_id',$request['USER_BUS_OPERATOR_ID']);
        }
        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) {
            $paginate = 10 ;
        }

        if(!empty($name))
        {

            $data = $data->where(
                function($query) use ($name) {
                    $data = $query->whereHas('busOperator', function ($query) use ($name) {$query->where('operator_name',  'like', '%' .$name . '%' );})
                        ->orwhere('created_by', 'like', '%' .$name . '%')
                        ->orwhere('coupon_title', 'like', '%' .$name . '%')
                        ->orwhere('coupon_code', 'like', '%' .$name . '%')
                        ->orderBy('id','DESC');
            }); 
        }

        $data=$data->paginate($paginate);

        if($data){
                foreach($data as $v){ 
                   if($v->source_id!=null && $v->destination_id!=null)
                   {
                      $v['from_location']=$this->location->where('id', $v->source_id)->get();
                    $v['to_location']=$this->location->where('id', $v->destination_id)->get(); 
                   }
                   else
                   {
                     $v['from_location']=null;
                     $v['to_location']=null; 
                   }
            }
        }     

        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
            "data" => $data
           );   
           return $response;      

    }

    
    public function delete($id)
    {       
        $coupons = $this->coupon->find($id);
        $coupons->status = 2;
        

        $coupons->update();

        return $coupons;

       
    }
    public function changeStatus($id)
    {
      $post = $this->coupon->find($id);
        if($post->status==0){
            $post->status = 1;
        }elseif($post->status==1){
            $post->status = 0;
        }
        $post->update();
        return $post;
    }

}