<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CustomerQuery;

use App\Services\CustomerQueryService;
use Exception;
use Illuminate\Support\Facades\Validator;

class CustomerQueryController extends Controller
{
     /**
     * @var customerQueryService
     */
    protected $customerQueryService;

    /**
     * PostController Constructor
     *
     * @param CustomerQueryService $customerQueryService
     *
     */
    public function __construct(CustomerQueryService $customerQueryService)
    {
        $this->customerQueryService = $customerQueryService;
    }



    public function getAllCustomerQuery() {
        $prod = $this->customerQueryService->getAll();;
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$prod;
        return response($output, 200);
    }
    public function validateData($data)
    {
        
        $validator = Validator::make($data, [
          'email' => 'required',
          'phone' => 'required|max:12',
          'query_typ' => 'required',
          'data' => 'required'
        ]);

        if ($validator->fails()) {
          throw new InvalidArgumentException($validator->errors()->first());
        }
    }
    public function createCustomerQuery(Request $request) {
        $data = $request->only([
            'email','phone', 'query_typ','data',
          
        ]);
        // validateData($data);
        // $validator = Validator::make($data, [
        //     'email' => 'required',
        //     'phone' => 'required',
        //     'query_typ' => 'required',
        //     'data' => 'required'
        // ]);
  
        // if ($validator->fails()) {
        //     throw new InvalidArgumentException($validator->errors()->first());
        // }
        $customerQueryRules = [
            'email' => 'required',
            'phone' => 'required|max:12',
            'query_typ' => 'required',
            'data' => 'required'
        ];
        
        $customerQueryValidation = Validator::make($data, $customerQueryRules);
        // $errors = $customerValidation->errors();

        // return $errors->toJson();
        if ($customerQueryValidation->fails()) {
            $errors = $customerQueryValidation->errors();
            return $errors->toJson();
          }

        $this->customerQueryService->savePostData($data);
    
        $output ['status']=1;
        $output ['message']='Data Added Successfully';
        return response($output, 200);
	
    } 

    public function updateCustomerQuery(Request $request, $id) {
        $data = $request->only([
            'email','phone', 'query_typ','data',
          
        ]);
        // validateData($data);
        $customerQueryRules = [
            'email' => 'required',
            'phone' => 'required',
            'query_typ' => 'required',
            'data' => 'required'
        ];
        
        $customerQueryValidation = Validator::make($data, $customerQueryRules);
        // $errors = $customerValidation->errors();

        // return $errors->toJson();
        if ($customerQueryValidation->fails()) {
            $errors = $customerQueryValidation->errors();
            return $errors->toJson();
          }
        $this->customerQueryService->updatePost($data, $id);
        $output ['status']=1;
        $output ['message']='Data updated successfully';
        return response($output, 200);
        
    }

    public function deleteCustomerQuery ($id) {
      $this->customerQueryService->deleteById($id);
      $output ['status']=1;
      $output ['message']='Data Deleted successfully';
      return response($output, 200);
    }

    public function getCustomerQuery($id) {
      $ame= $this->customerQueryService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$ame;
      return response($output, 200);


  	}
}
