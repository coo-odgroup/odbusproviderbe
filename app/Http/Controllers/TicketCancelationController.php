<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketCancelation;
use App\Models\TicketCancelationRule;
use Illuminate\Support\Facades\Validator;
use App\Services\TicketCancelationService;
use App\Repositories\TicketCancelationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use InvalidArgumentException;

class TicketCancelationController extends Controller
{
    
    protected $ticketCancelationService;
    protected $ticketCancelationRepository;

    
    public function __construct(TicketCancelationService $ticketCancelationService,
                                TicketCancelationRepository $ticketCancelationRepository)
    {
        $this->ticketCancelationService = $ticketCancelationService;
        $this->ticketCancelationRepository = $ticketCancelationRepository;
    }
    // public function getAllTicketCancelations() {

    //     $ticketcancelation = $this->ticketCancelationService->getAll();
    //     $ticketcancel ['status']=1;
    //     $ticketcancel ['message']='All Data Fetched Successfully';
    //     $ticketcancel ['result']=$ticketcancelation;

    //     return response($ticketcancel, 200);
    // }

    public function getAllTicketCancelations() {

        $ticketcancelation = $this->TicketCancelationRepository->getAll();
        $ticketcancel ['status']=1;
        $ticketcancel ['message']='All Data Fetched Successfully';
        $ticketcancel ['result']=$ticketcancelation;

        return response($ticketcancel, 200);
    }
    public function createTicketCancelations(Request $request) {
        
        $data = $request->only(['name','created_by']);
        
        $ticketCancelationRules = [
          'name' => 'required',
          'created_by' => 'required'

        ];

        $ticketCancelationruleRules = [
          'hour_lag_start' => 'required',
          'hour_lag_end' => 'required',
          'cancelation_percentage' => 'required',
          'created_by' => 'required'

        ];
        $ticketCancelationRule=$request->input('ticketCancelationRule');
      
      // $userValidation = Validator::make($inputs, $userRules);

      foreach($request['ticketCancelationRule'] as $ticketCancelation_rule){
        $ticketCancelationruleValidation = Validator::make($ticketCancelation_rule, $ticketCancelationruleRules);
        if ($ticketCancelationruleValidation->fails()) {
          // throw new InvalidArgumentException($locationCodeValidation->errors()->first());
          $errors = $ticketCancelationruleValidation->errors();

          return $errors->toJson();
          exit;

        }
          
      } 
       
  
        $result = ['status' => 200];
  
        try {
            //$result['data'] = $this->ticketCancelationService->savePostData($request);
            $result['data'] = $this->ticketCancelationRepository->save($request->all());
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
      
      } 
      // public function getTicketCancelationsbyID($id) {
            
      //   $ticketcancelation = $this->ticketCancelationService->getById($id);
      //   $ticketcancel ['status']=1;
      //   $ticketcancel ['message']='Single Data Fetched Successfully';
      //   $ticketcancel ['result']=$ticketcancelation;
      //   return response($ticketcancel, 200);

      //   }

       public function getTicketCancelationsbyID($id) {
            
        $ticketcancelation = $this->TicketCancelationRepository->getById($id);
        $ticketcancel ['status']=1;
        $ticketcancel ['message']='Single Data Fetched Successfully';
        $ticketcancel ['result']=$ticketcancelation;
        return response($ticketcancel, 200);

        }

}
