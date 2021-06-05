<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Validator;
use App\Services\AppVersionService;
use Exception;
use Middleware;
use InvalidArgumentException;

class AppVersionController extends Controller
{
    
    protected $appVersionService;

    
    public function __construct(AppVersionService $appVersionService)
    {
        $this->appVersionService = $appVersionService;
    }


    public function getAllAppVersion() {

        $appVersion = $this->appVersionService->getAll();
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$appVersion;
        return response($output, 200);
    }

    public function createAppVersion(Request $request) {
        $data = $request->only([
        
            'info','name', 'mandatory','version','new_version_names','new_version_codes', 'allowed_days', 
            'has_issues','created_by' 
          ]);

          $appversionRules = [
            'info' => 'required',
            'name' => 'required',
            'mandatory' => 'required',
            'version' => 'required',
            'new_version_names' => 'required',
            'new_version_codes' => 'required',
            'allowed_days' => 'required',
            'has_issues' => 'required',
            'created_by' => 'required',

        ];
        
        $appversionValidation = Validator::make($data, $appversionRules);


        if ($appversionValidation->fails()) {
            $errors = $appversionValidation->errors();
            return $errors->toJson();
          }
       /* $errors = $appversionValidation->errors();

        return $errors->toJson();

        $this->appVersionService->savePostData($data);
    
        $output ['status']=1;
        $output ['message']='Data Added Successfully';
        return response($output, 200);*/

        
       /* $validator = Validator::make($data, [

            'info' => 'required',
            'name' => 'required',
            'mandatory' => 'required',
            'version' => 'required',
            'new_version_names' => 'required',
            'new_version_codes' => 'required',
            'allowed_days' => 'required',
            'has_issues' => 'required',
            'created_by' => 'required'
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }*/
        
      

      $result = ['status' => 200];

      try {
          $result['data'] = $this->appVersionService->savePostData($data);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }

      return response()->json($result, $result['status']);

    } 

    public function updateAppVersion(Request $request, $id) {
        $data = $request->only(['info','name', 'mandatory','version','new_version_names','new_version_codes', 
        'allowed_days', 'has_issues', 'created_by'
        ]);
        $appversionRules = [
            'info' => 'required',
            'name' => 'required',
            'mandatory' => 'required',
            'version' => 'required',
            'new_version_names' => 'required',
            'new_version_codes' => 'required',
            'allowed_days' => 'required',
            'has_issues' => 'required',
            'created_by' => 'required',

        ];
        
        $appversionValidation = Validator::make($data, $appversionRules);


        if ($appversionValidation->fails()) {
            $errors = $appversionValidation->errors();
            return $errors->toJson();
          }


        $result = ['status' => 200];

        try {
            $result['data'] = $this->appVersionService->updatePost($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function deleteAppVersion ($id) {
      $result = ['status' => 200];

      try {
          $result['data'] = $this->appVersionService->deleteById($id);
      } catch (Exception $e) {
          $result = [
              'status' => 500,
              'error' => $e->getMessage()
          ];
      }
      return response()->json($result, $result['status']);
    }

    public function getAppVersion($id) {
      $app = $this->appVersionService->getById($id);
      $output ['status']=1;
      $output ['message']='Single Data Fetched Successfully';
      $output ['result']=$app;
      return response($output, 200);
    }      
	     

}
