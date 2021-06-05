<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CustomerQueryCategory;
use App\Models\CustomerQueryCategoryIssues;

use Illuminate\Support\Str;

use App\Services\CustomerQueryCategoryService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

use Exception;

class CustomerQueryCategoryController extends Controller
{
    /**
     * @var CustomerQueryCategoryService
     */
    protected $customerQueryCategoryService;

    /**
     * PostController Constructor
     *
     * @param CustomerQueryCategoryService $busTypeService
     *
     */
    public function __construct(CustomerQueryCategoryService $customerQueryCategoryService)
    {
        $this->customerQueryCategoryService = $customerQueryCategoryService;
    }
    public function getAllCustomerQueryCategory() {

        $users = $this->customerQueryCategoryService->getAll();
        $user ['status']=1;
        $user ['message']='All Data Fetched Successfully';
        $user ['result']=$users;
        return response($user, 200);
    }
      public function createCustomerQueryCategory(Request $request) {
        
        $data = $request->only([
			'name'
		]);
        
        $customerQueryCategoryRules = [
          'name' => 'required'
		];
		$customerQueryCategoryValidation = Validator::make($data, $customerQueryCategoryRules);
        
        if ($customerQueryCategoryValidation->fails()) {
          $errors = $customerQueryCategoryValidation->errors();
          return $errors->toJson();
        }

        $customerQueryCategoryIssuesRules = [
          'name' => 'required',
          

        ];
        $customerQueryCategoryIssues=$request->input('customerQueryCategoryIssues');
      
      // $userValidation = Validator::make($inputs, $userRules);

      foreach($request['customerQueryCategoryIssues'] as $customerQueryCategoryIssues){
        $userCodeValidation = Validator::make($customerQueryCategoryIssues, $customerQueryCategoryIssuesRules);
        if ($userCodeValidation->fails()) {
          // throw new InvalidArgumentException($userCodeValidation->errors()->first());
          $errors = $userCodeValidation->errors();

          return $errors->toJson();
        //   exit;

        }
          
      }   
    
	//   print_r("ddskjhdkd"); exit;
        $result = ['status' => 200];
  
        try {
            $result['data'] = $this->customerQueryCategoryService->savePostData($request);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
      
      } 

      public function getCustomerQueryCategorybyID($id) {
        //print_r("hello");exit();     
        $users = $this->customerQueryCategoryService->getById($id);
        $user ['status']=1;
        $user ['message']='Single Data Fetched Successfully';
        $user ['result']=$users;
        return response($user, 200);

	}
} 
