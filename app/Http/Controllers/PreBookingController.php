<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PreBooking;
use App\Models\PreBookingDetail;
//use App\Models\Bus;
use Illuminate\Support\Facades\Validator;
use App\Repositories\PreBookingRepository;
use App\Services\PreBookingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class PreBookingController extends Controller
{
    protected $preBookingService;
    protected $preBookingRepository;
    
    public function __construct(PreBookingService $preBookingService,
                                PreBookingRepository $preBookingRepository  )
    {
        $this->preBookingService = $preBookingService;
        $this->preBookingRepository = $preBookingRepository;
    }


    public function getAllPreBooking() {

        //$preBooking = $this->preBookingService->getAll();
        $preBooking = $this->preBookingRepository->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$preBooking;
        return response($output, 200);
    }

    public function createPreBooking(Request $request) {
        $data = $request->only([

            'transaction_id','user_id','bus_id','j_day','journey_dt','bus_info',
            'customer_info','total_fare','is_coupon','coupon_code','coupon_discount',
            'discounted_fare','customer_id','created_by',
          ]);
          //var_dump($data);
          $preBookingRules = [
            //'transaction_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'customer_id' => 'required',
            'created_by' => 'required',
             
        ];
        $PreBookingValidation = Validator::make($data, $preBookingRules);
        
        if ($PreBookingValidation->fails()) {
          $errors = $PreBookingValidation->errors();
          return $errors->toJson();
        }
        $preBookingDetailRules = [
            
            'journey_date' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'seat_name' => 'required',
            'created_by' => 'required'
  
          ];
         
        $preBookingDetail=$request->input('preBookingDetail');

        //$preBookingValidation = Validator::make($data, $prebookingRules);
        //print_r($prebookingDetails);
       // var_dump($prebookingDetail);

        foreach($request['preBookingDetail'] as $preBooking_Detail){
            $preBookingValidation = Validator::make($preBooking_Detail, $preBookingDetailRules);
            if ($preBookingValidation->fails()) {
              // throw new InvalidArgumentException($locationCodeValidation->errors()->first());
              $errors = $preBookingValidation->errors();
    
              return $errors->toJson();
             // exit;
            }   
          } 
           
        $result = ['status' => 200];
        try {

        // $busRequest = $busService->getBusListingReplica();
            //var_dump($busRequest);
           // $request['bus_info'] = $busRequest;

             //$result['data'] = $this->preBookingService->savePreBookingData($request);
              $result['data'] = $this->preBookingService->savePreBooking($request);
        } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
        }

        return response()->json($result, $result['status']);

    } 

    public function updatePreBooking(Request $request, $id) {
        $data = $request->only([
            'transaction_id','user_id','bus_id','j_day','journey_dt','bus_info',
            'customer_info','total_fare','is_coupon','coupon_code','coupon_discount',
            'discounted_fare','customer_id','created_by'
            
        ]);
        $preBookingRules = [
            
            'transaction_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'customer_id' => 'required',
            'created_by' => 'required',
            
        ];
        
        $preBookingValidation = Validator::make($data, $preBookingRules);


        if ($preBookingValidation->fails()) {
            $errors = $preBookingValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];
        DB::beginTransaction();

        try {
           // $result['data'] = $this->preBookingService->updatePost($data, $id);
            $result['data'] = $this->preBookingRepository->update($data, $id);
            DB::commit();


        } catch (Exception $e) {
          DB::rollBack();
          Log::info($e->getMessage());
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deletePreBooking($id) {
      $result = ['status' => 200];

     DB::beginTransaction(); 
      try {
          //$result['data'] = $this->preBookingService->deleteById($id);
          $result['data'] = $this->preBookingRepository->delete($id);
          DB::commit();
      } catch (Exception $e) {
          DB::rollBack();
          Log::info($e->getMessage());
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getPreBooking($id) {
      //$couponassignedBus = $this->preBookingService->getById($id);
      $couponassignedBus = $this->preBookingRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$couponassignedBus;
      return response($output, 200);
    }      
         

    /*****************************/
    /****Pre_booking_phase_one****/
    
    public function createPreBookingPhaseOne(Request $request) {
        $data = $request->only([

            'transaction_id','user_id','bus_id','j_day','journey_dt','bus_info',
            'customer_info','total_fare','is_coupon','coupon_code','coupon_discount',
            'discounted_fare','customer_id','created_by',
          ]);
          //var_dump($data);
          $preBookingRules = [
            //'transaction_id' => 'required',
            'user_id' => 'required',
            'bus_id' => 'required',
            'j_day' => 'required',
            'journey_dt' => 'required',
            'bus_info' => 'required',
            'customer_info' => 'required',
            'total_fare' => 'required',
            'is_coupon' => 'required',
            'coupon_code' => 'required',
            'coupon_discount' => 'required',
            'discounted_fare' => 'required',
            'customer_id' => 'required',
            'created_by' => 'required',
             
        ];
        $PreBookingValidation = Validator::make($data, $preBookingRules);
        
        if ($PreBookingValidation->fails()) {
          $errors = $PreBookingValidation->errors();
          return $errors->toJson();
        }
        $preBookingDetailRules = [
            
            'journey_date' => 'required',
            'j_day' => 'required',
            'bus_id' => 'required',
            'seat_name' => 'required',
            'created_by' => 'required'
  
          ];
         
        $preBookingDetail=$request->input('preBookingDetail');

        //$preBookingValidation = Validator::make($data, $prebookingRules);
        //print_r($prebookingDetails);
       // var_dump($prebookingDetail);

        foreach($request['preBookingDetail'] as $preBooking_Detail){
            $preBookingValidation = Validator::make($preBooking_Detail, $preBookingDetailRules);
            if ($preBookingValidation->fails()) {
              // throw new InvalidArgumentException($locationCodeValidation->errors()->first());
              $errors = $preBookingValidation->errors();
    
              return $errors->toJson();
             // exit;
            }   
          } 
           
        $result = ['status' => 200];
               $data = $request->all();
        try {
          
        // $busRequest = $busService->getBusListingReplica();
            //var_dump($busRequest);
           // $request['bus_info'] = $busRequest;

            // $result['data'] = $this->preBookingService->savePreBookingData($request);
              $result['data'] = $this->preBookingService->savePreBooking($data);
        } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
        }

        return response()->json($result, $result['status']);

    } 

/*****************************/
    /****Pre_booking_phase_two****/

    public function updatePreBookingPhaseTwo(Request $request, $transaction_id) {
        $data = $request->only([
            //'transaction_id',
            'customer_info',
            'customer_id',
            
        ]);
        $preBookingPhaseTwoRules = [
            
            //'transaction_id' => 'required',
            'customer_info' => 'required',
            'customer_id' => 'required',
            
            
        ];
        
        $preBookingPhaseTwoValidation = Validator::make($data, $preBookingPhaseTwoRules);


        if ($preBookingPhaseTwoValidation->fails()) {
            $errors = $preBookingPhaseTwoValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];
        DB::beginTransaction();

        try {
            //$result['data'] = $this->preBookingService->updatePreBookingPhaseTwo($data, $transaction_id);
            $result['data'] = $this->preBookingRepository->updatePreBookingTwo($data, $transaction_id);
         DB::commit();
        } catch (Exception $e) {
          DB::rollBack();
          Log::info($e->getMessage());
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

}
