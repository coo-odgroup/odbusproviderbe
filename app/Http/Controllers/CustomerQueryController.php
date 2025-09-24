<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerQuery;
use App\Repositories\CustomerQueryRepository;
use Exception;
use App\Services\CustomerQueryService;

use Illuminate\Support\Facades\Validator;

class CustomerQueryController extends Controller
{
     /**
     * @var customerQueryService
     */
    protected $customerQueryService;
    protected $customerQueryRepository;

    /**
     * PostController Constructor
     *
     * @param CustomerQueryService $customerQueryService
     *
     */
    public function __construct(CustomerQueryService $customerQueryService,
                                CustomerQueryRepository $customerQueryRepository)
    {
        $this->customerQueryService = $customerQueryService;
        $this->customerQueryRepository = $customerQueryRepository;
    }



    public function getAllCustomerQuery() {
        //$prod = $this->customerQueryService->getAll();
        $prod = $this->customerQueryRepository->getAll();
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

        //$this->customerQueryService->savePostData($data);
        $this->customerQueryRepository->save($data);
    
        $output ['status']=1;
        $output ['message']='Data Added Successfully';
        return response($output, 200);
	
    } 

    // public function updateCustomerQuery(Request $request, $id) {
    //     $data = $request->only([
    //         'email','phone', 'query_typ','data',
          
    //     ]);
    //     // validateData($data);
    //     $customerQueryRules = [
    //         'email' => 'required',
    //         'phone' => 'required',
    //         'query_typ' => 'required',
    //         'data' => 'required'
    //     ];
        
    //     $customerQueryValidation = Validator::make($data, $customerQueryRules);
    //     // $errors = $customerValidation->errors();

    //     // return $errors->toJson();
    //     if ($customerQueryValidation->fails()) {
    //         $errors = $customerQueryValidation->errors();
    //         return $errors->toJson();
    //       }
    //     $this->customerQueryService->updatePost($data, $id);
    //     $output ['status']=1;
    //     $output ['message']='Data updated successfully';
    //     return response($output, 200);
        
    // }


    public function updateCustomerQuery(Request $request, $id) {
    $data = $request->only([
        'email','phone', 'query_typ','data',
    ]);

    $customerQueryRules = [
        'email' => 'required',
        'phone' => 'required',
        'query_typ' => 'required',
        'data' => 'required'
    ];
    
    
    $customerQueryValidation = Validator::make($data, $customerQueryRules);

    if ($customerQueryValidation->fails()) {
        $errors = $customerQueryValidation->errors();
        return response()->json([
            'status' => 0,
            'errors' => $errors
        ], 422);
    }

   
    DB::beginTransaction();
    try {
        $post = $this->customerQueryRepository->update($data, $id);
        DB::commit();

        return response()->json([
            'status' => 1,
            'message' => 'Data updated successfully',
            'data' => $post
        ], 200);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error("Update failed: " . $e->getMessage());

        return response()->json([
            'status' => 0,
            'message' => 'Unable to update post data'
        ], 500);
    }
}

    // public function deleteCustomerQuery ($id) {
    //   $this->customerQueryService->deleteById($id);
    //   $output ['status']=1;
    //   $output ['message']='Data Deleted successfully';
    //   return response($output, 200);
    // }

    public function deleteCustomerQuery($id) {
    DB::beginTransaction();

    try {
        
        $this->customerQueryRepository->delete($id);

        DB::commit();

        $output['status'] = 1;
        $output['message'] = 'Data Deleted successfully';
        return response()->json($output, 200);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error("Delete failed: " . $e->getMessage());

        $output['status'] = 0;
        $output['message'] = 'Unable to delete post data';
        return response()->json($output, 500);
    }
}

    public function getCustomerQuery($id) {
      //$ame= $this->customerQueryService->getById($id);
        $ame=  $this->customerQueryRepository->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$ame;
      return response($output, 200);


  	}
}
