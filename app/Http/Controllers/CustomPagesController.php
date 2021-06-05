<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomPages;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomPagesService;
use Exception;
use InvalidArgumentException;


class CustomPagesController extends Controller
{
    protected $customPagesService;

    
    public function __construct(CustomPagesService $customPagesService)
    {
        $this->customPagesService = $customPagesService;
    }


    public function getAllcustomPages() {

        $custompages = $this->customPagesService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$custompages;
        return response($output, 200);
    }

    public function createcustomPages(Request $request) {
        $data = $request->only([
        
            'origin','type','source_id','destination_id','name','url',
            'content','meta_title','meta_keyword','meta_descriptiom','created_by' 
          ]);

          $customPagesRules = [
            'origin' => 'required',
            'type' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'name' => 'required',
            'url' => 'required',
            'content' => 'required',
            'meta_title' => 'required',
            'meta_keyword' => 'required',
            'meta_descriptiom' => 'required',
            'created_by' => 'required',

        ];
        
        $customPagesValidation = Validator::make($data, $customPagesRules);


        if ($customPagesValidation->fails()) {
            $errors = $customPagesValidation->errors();
            return $errors->toJson();
          }
       

      $result = ['status' => 200];

      try {
          $result['data'] = $this->customPagesService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updatecustomPages(Request $request, $id) {
        $data = $request->only([
        
            'origin','type','source_id','destination_id','name','url',
            'content','meta_title','meta_keyword','meta_descriptiom','created_by' 
          ]);

          $customPagesRules = [
            'origin' => 'required',
            'type' => 'required',
            'source_id' => 'required',
            'destination_id' => 'required',
            'name' => 'required',
            'url' => 'required',
            'content' => 'required',
            'meta_title' => 'required',
            'meta_keyword' => 'required',
            'meta_descriptiom' => 'required',
            'created_by' => 'required',

        ];
        
        $customPagesValidation = Validator::make($data, $customPagesRules);


        if ($customPagesValidation->fails()) {
            $errors = $customPagesValidation->errors();
            return $errors->toJson();
          }
       

      $result = ['status' => 200];

      try {
          $result['data'] = $this->customPagesService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 
    public function deletecustomPages ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->customPagesService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getcustomPages($id) {
      $app = $this->customPagesService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$app;
      return response($output, 200);
    }      
	     
}
