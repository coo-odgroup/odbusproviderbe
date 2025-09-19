<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteMaster;
use Illuminate\Support\Facades\Validator;
use App\Services\SiteMasterService;
use App\Repositories\SiteMasterRepository;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiteMasterController extends Controller
{
    protected $siteMasterService;
    protected $siteMasterRepository;

    
    public function __construct(//SiteMasterService $siteMasterService,
                                SiteMasterRepository $siteMasterRepository)
    {
       // $this->siteMasterService = $siteMasterService;
        $this->siteMasterRepository = $siteMasterRepository;
    }


    // public function getAllSiteMaster() {

    //     $siteMaster = $this->siteMasterService->getAll();
    //     $output ['status']=1;
    //     $output ['message']='All Data Fetched Successfully';
    //     $output ['result']=$siteMaster;
    //     return response($output, 200);
    // }
    public function getAllSiteMaster() {

        $siteMaster = $this->siteMasterRepository->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$siteMaster;
        return response($output, 200);
    }

    public function createSiteMaster(Request $request) {
        $data = $request->only([
        
            'site_live','live_at','extra_price','version','calender_days','service_charge', 'per_trasaction', 
            'max_seat_booked','support_email','booking_email','request_email','other_email','contact_no1',
            'contact_no2','contact_no3', 'contact_no4', 'facebook_url','twitter_url','linkedin_url', 'instagram_url', 
            'googleplus_url','min_fare_amt','earned_pts',

          ]);
        
          $sitemasterRules = [

    
            'site_live' => 'required',
            'live_at' => 'required',
            'extra_price' => 'required',
            'calender_days' => 'required',
            'service_charge' => 'required',
            'per_trasaction' => 'required',
            'max_seat_booked' => 'required',
            'support_email' => 'required',
            'booking_email' => 'required',
            'request_email' => 'required',
            'other_email' => 'required',
            'contact_no1' => 'required',
            'contact_no2' => 'required',
            'contact_no3' => 'required',
            'contact_no4' => 'required',
            'facebook_url' => 'required',
            'twitter_url' => 'required',
            'linkedin_url' => 'required',
            'instagram_url' => 'required',
            'googleplus_url' => 'required',
            'min_fare_amt' => 'required',
            'earned_pts' => 'required'
            
        ];
        $sitemasterValidation = Validator::make($data, $sitemasterRules);


        if ($sitemasterValidation->fails()) {
            $errors = $sitemasterValidation->errors();
            return $errors->toJson();
          }

        //print_r($validator);exit();  

        

      $result = ['status' => 200];

      try {
          //$result['data'] = $this->siteMasterService->savePostData($data);
            $result['data'] = $this->siteMasterRepository->save($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    // public function updateSiteMaster(Request $request, $id) {
    //     $data = $request->only([
    //         'site_live','live_at','extra_price','version','calender_days','service_charge', 'per_trasaction', 
    //         'max_seat_booked','support_email','booking_email','request_email','other_email','contact_no1',
    //         'contact_no2','contact_no3', 'contact_no4', 'facebook_url','twitter_url','linkedin_url', 'instagram_url', 
    //         'googleplus_url','min_fare_amt','earned_pts',

    //     ]);
    //     $sitemasterRules = [

    
    //         'site_live' => 'required',
    //         'live_at' => 'required',
    //         'extra_price' => 'required',
    //         'calender_days' => 'required',
    //         'service_charge' => 'required',
    //         'per_trasaction' => 'required',
    //         'max_seat_booked' => 'required',
    //         'support_email' => 'required',
    //         'booking_email' => 'required',
    //         'request_email' => 'required',
    //         'other_email' => 'required',
    //         'contact_no1' => 'required',
    //         'contact_no2' => 'required',
    //         'contact_no3' => 'required',
    //         'contact_no4' => 'required',
    //         'facebook_url' => 'required',
    //         'twitter_url' => 'required',
    //         'linkedin_url' => 'required',
    //         'instagram_url' => 'required',
    //         'googleplus_url' => 'required',
    //         'min_fare_amt' => 'required',
    //         'earned_pts' => 'required'
            
    //     ];
    //     $sitemasterValidation = Validator::make($data, $sitemasterRules);


    //     if ($sitemasterValidation->fails()) {
    //         $errors = $sitemasterValidation->errors();
    //         return $errors->toJson();
    //       }

    //     $result = ['status' => 200];

    //     try {
    //         $result['data'] = $this->siteMasterService->updatePost($data, $id);

    //     } catch (Exception $e) {
    //         $result = [
    //             'status' => 500,
    //             'error' => $e->getMessage()
    //         ];
    //     }

    //     return response()->json($result, $result['status']);
    // }

    public function updateSiteMaster(Request $request, $id) {
    $data = $request->only([
        'site_live','live_at','extra_price','version','calender_days','service_charge','per_trasaction',
        'max_seat_booked','support_email','booking_email','request_email','other_email','contact_no1',
        'contact_no2','contact_no3','contact_no4','facebook_url','twitter_url','linkedin_url','instagram_url',
        'googleplus_url','min_fare_amt','earned_pts',
    ]);

    $sitemasterRules = [
        'site_live' => 'required',
        'live_at' => 'required',
        'extra_price' => 'required',
        'calender_days' => 'required',
        'service_charge' => 'required',
        'per_trasaction' => 'required',
        'max_seat_booked' => 'required',
        'support_email' => 'required',
        'booking_email' => 'required',
        'request_email' => 'required',
        'other_email' => 'required',
        'contact_no1' => 'required',
        'contact_no2' => 'required',
        'contact_no3' => 'required',
        'contact_no4' => 'required',
        'facebook_url' => 'required',
        'twitter_url' => 'required',
        'linkedin_url' => 'required',
        'instagram_url' => 'required',
        'googleplus_url' => 'required',
        'min_fare_amt' => 'required',
        'earned_pts' => 'required'
    ];


    $sitemasterValidation = Validator::make($data, $sitemasterRules);
    if ($sitemasterValidation->fails()) {
        $errors = $sitemasterValidation->errors();
        return response()->json([
            'status' => 422,
            'errors' => $errors
        ], 422);
    }

    $result = ['status' => 200];
    DB::beginTransaction();

    try {
      
        $result['data'] = $this->siteMasterRepository->update($data, $id);

        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();

        $result = [
            'status' => 500,
            'error' => 'Unable to update post data',

        ];

        return response()->json($result, 500);
    }

    return response()->json($result, 200);
}


    // public function deleteSiteMaster ($id) {
    //   $result = ['status' => 200];

    //   try {
    //       $result['data'] = $this->siteMasterService->deleteById($id);
    //   } catch (Exception $e) {
    //       $result = [
    //           'status' => 500,
    //           'error' => $e->getMessage()
    //       ];
    //   }
    //   return response()->json($result, $result['status']);
    // }
    public function deleteSiteMaster($id)
{
    $result = ['status' => 200];
    DB::beginTransaction();

    try {
       
        $result['data'] = $this->siteMasterRepository->delete($id);

        DB::commit();
    } catch (Exception $e) {
        DB::rollBack();

        $result = [
            'status' => 500,
            'error'  => 'Unable to delete post data',
           
        ];

        return response()->json($result, 500);
    }

    return response()->json($result, 200);
}


    // public function getSiteMaster($id) {
    //   $app = $this->siteMasterService->getById($id);
    //   $output ['status']=1;
    //   $output ['message']='Single Data Fetched Successfully';
    //   $output ['result']=$app;
    //   return response($output, 200);
    // }    
    public function getSiteMaster($id) {
      $app =  $this->siteMasterRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$app;
      return response($output, 200);
    }      
	     
    
    


}
