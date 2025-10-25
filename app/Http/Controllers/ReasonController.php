<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reason;
use Illuminate\Support\Facades\Validator;
use App\Services\ReasonService;
use Exception;
use InvalidArgumentException;

class ReasonController extends Controller
{
    protected $reasonService;

    
    public function __construct(ReasonService $reasonService)
    {
        $this->reasonService = $reasonService;
    }


    public function getAllReason() {

        $reason = $this->reasonService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$reason;
        return response($output, 200);
    }

    public function createReason(Request $request) {
        $data = $request->only([

            'name', 
            'created_by'
            
          ]);
        
          $reasonRules = [
            'name' => 'required',
            'created_by' => 'required',
             
        ];
        
        $reasonValidation = Validator::make($data, $reasonRules);


        if ($reasonValidation->fails()) {
            $errors = $reasonValidation->errors();
            return $errors->toJson();
          }
      $result = ['status' => 200];

      try {
          $result['data'] = $this->reasonService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateReason(Request $request, $id) {
        $data = $request->only([
            'name',
            'created_by', 
            
        ]);
        $reasonRules = [
            
            'name' => 'required',
            'created_by' => 'required',
            
        ];
        
        $reasonValidation = Validator::make($data, $reasonRules);


        if ($reasonValidation->fails()) {
            $errors = $reasonValidation->errors();
            return $errors->toJson();
          }

        $result = ['status' => 200];

        try {
            $result['data'] = $this->reasonService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteReason ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->reasonService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getReason($id) {
      $reason = $this->reasonService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$reason;
      return response($output, 200);
    }      
	     

}
