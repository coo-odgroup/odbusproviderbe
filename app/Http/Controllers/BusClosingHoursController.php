<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BusClosingHours;
use App\Services\BusClosingHourService;
use App\Repositories\BusClosingHourRepository;
use Illuminate\Support\Facades\Log;
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
      protected $busClosingHourRepository;
    public function __construct(BusClosingHourService $busClosingHourService,
                                 BusClosingHourValidator $busClosingHourvalidator,
                                 BusClosingHourRepository $busClosingHourRepository)
    {
        $this->busClosingHourService = $busClosingHourService;
        $this->BusClosingHourValidator = $busClosingHourvalidator;
        $this->busClosingHourRepository = $busClosingHourRepository;
    }
    public function getAllClosingHours(Request $request) {
        //$bClosingHours = $this->busClosingHourService->getAll($request);
        $bClosingHours = $this->busClosingHourRepository->getAll();
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAllClosingHoursDataTable(Request $request) {
        //$bClosingHours = $this->busClosingHourService->dataTable($request);
        $bClosingHours = $this->busClosingHourRepository->getDatatable($request);
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function deleteClosingHours ($id) {
        try {
           // $this->busClosingHourService->deleteById($id);
            $this->busClosingHourRepository->delete($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse(null,Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }
    public function getClosingHours($id) {
        try {
           // $bClosingHours= $this->busClosingHourService->getById($id);
            $bClosingHours= $this->busClosingHourRepository->getById($id);
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
            //$this->busClosingHourService->savePostData($data);
            $this->busClosingHourRepository->create($data);
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
           // $this->busClosingHourService->updatePost($data, $id);
            $this->busClosingHourRepository->update($data, $id);
            return $this->successResponse(Null,Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }
}
