<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BusClosingHours;
use App\Services\BusClosingHourService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\BusClosingHourValidator;
use Symfony\Component\HttpFoundation\Response;


class BusClosingHoursController extends Controller
{
    //
    use ApiResponser;
    protected $busClosingHourvalidator;
    protected $busClosingHourService;
    public function __construct(BusClosingHourService $busClosingHourService, BusClosingHourValidator $busClosingHourvalidator)
    {
        $this->busClosingHourService = $busClosingHourService;
        $this->BusClosingHourValidator = $busClosingHourvalidator;
    }
    public function getAllClosingHours(Request $request) {
        $bClosingHours = $this->busClosingHourService->getAll($request);
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAllClosingHoursDataTable(Request $request) {
        $bClosingHours = $this->busClosingHourService->dataTable($request);
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function deleteClosingHours ($id) {
        try {
            $this->busClosingHourService->deleteById($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse(null,Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }
    public function getClosingHours($id) {
        try {
            $bClosingHours= $this->busClosingHourService->getById($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function createClosingHours(Request $request) {
        $data = $request->only([
            'bus_id', 'city_id', 'dep_time','closing_hours'
            ]);
        
        $bClosingHourValidate = $this->BusClosingHourValidator->validate($data);
    
        if ($bClosingHourValidate->fails()) {
            $errors = $bClosingHourValidate->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busClosingHourService->savePostData($data);
            return $this->successResponse(Null,Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }

    }
    public function updateClosingHours (Request $request, $id) {
        $data = $request->only([
            'bus_id', 'city_id', 'dep_time','closing_hours'
            ]);
        
        $bClosingHourValidate = $this->BusClosingHourValidator->validate($data);
        
        if ($bClosingHourValidate->fails()) {
            $errors = $bClosingHourValidate->errors();
            // return $errors->toJson();
            return $this->errorResponse($errors->toJson(),Response::HTTP_PARTIAL_CONTENT);
        }
        try {
            $this->busClosingHourService->updatePost($data, $id);
            return $this->successResponse(Null,Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }
}
