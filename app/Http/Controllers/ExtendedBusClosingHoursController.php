<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ExtendedBusClosingHours;
use App\Services\ExtendedBusClosingHourService;
use App\Repositories\ExtendedBusClosingHourRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Traits\ApiResponser;
use Exception;
use InvalidArgumentException;
use App\AppValidator\ExtendedBusClosingHourValidator;
use Symfony\Component\HttpFoundation\Response;


class ExtendedBusClosingHoursController extends Controller
{
    //
    use ApiResponser;
    protected $extendedbusClosingHourvalidator;
    protected $busClosingHourService;
    protected $extendedbusClosingHourRepository;

    public function __construct(ExtendedBusClosingHourService $extendedbusClosingHourService, 
                                ExtendedBusClosingHourValidator $extendedbusClosingHourvalidator,
                                ExtendedBusClosingHourRepository $extendedbusClosingHourRepository)
    {
        $this->extendedbusClosingHourService = $extendedbusClosingHourService;
        $this->extendedbusClosingHourvalidator = $extendedbusClosingHourvalidator;
        $this->extendedbusClosingHourRepository = $extendedbusClosingHourRepository; 
    }
    public function getAllExtendedClosingHours(Request $request) {
        //$bClosingHours = $this->extendedbusClosingHourService->getAll($request);
        $bClosingHours = $this->extendedbusClosingHourRepository->getAll();
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function getAllExtendedClosingHoursDataTable(Request $request) {
       // $bClosingHours = $this->extendedbusClosingHourService->dataTable($request);
        $bClosingHours = $this->extendedbusClosingHourRepository->getDatatable($request);
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function deleteExtendedClosingHours ($id) {
        try {
            //$this->extendedbusClosingHourService->deleteById($id);
            $this->extendedbusClosingHourRepository->delete($id);
        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse(null,Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }
    public function getExtendedClosingHours($id) {
        try {
            //$bClosingHours= $this->extendedbusClosingHourService->getById($id);
            $bClosingHours = $this->extendedbusClosingHourRepository->getById($id);

        }
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($bClosingHours,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function createExtendedClosingHours(Request $request) {
        $data = $request->only([
            'bus_id', 'city_id', 'dep_time','closing_hours'
            ]);
        
        $extendedbClosingHourValidate = $this->extendedbusClosingHourvalidator->validate($data);
    
        if ($extendedbClosingHourValidate->fails()) {
            $errors = $extendedbClosingHourValidate->errors();
            return $errors->toJson();
        }
        try {
           // $this->extendedbusClosingHourService->savePostData($data);
              $this->extendedbusClosingHourRepository->save($data);
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
        
        $extendedbClosingHourValidate = $this->extendedbusClosingHourvalidator->validate($data);
        
        if ($extendedbClosingHourValidate->fails()) {
            $errors = $extendedbClosingHourValidate->errors();
            return $errors->toJson();
        }
        try {
           // $this->extendedbusClosingHourService->updatePost($data, $id);
                $this->extendedbusClosingHourRepository->update($data, $id);    
            return $this->successResponse(Null,Config::get('constants.RECORD_UPDATED'),Response::HTTP_CREATED);
        }
        catch(Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_NOT_FOUND);
        }
    }
}
