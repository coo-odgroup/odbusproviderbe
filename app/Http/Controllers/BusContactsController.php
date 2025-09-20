<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusContacts;
use App\Services\BusContactsService;
use Exception;
use App\Repositories\BusContactsRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use App\AppValidator\BusContactsValidator;
use Symfony\Component\HttpFoundation\Response;

class BusContactsController extends Controller
{
    use ApiResponser;
    protected $busContactsService;
    protected $BusContactsValidator;
     protected $busContactsRepository;

    public function __construct(BusContactsService $busContactsService, 
                                    BusContactsValidator $BusContactsValidator,
                                    BusContactsRepository $busContactsRepository)
    {
        $this->busContactsService = $busContactsService;
        $this->BusContactsValidator=$BusContactsValidator;
        $this->busContactsRepository = $busContactsRepository;
    }
    // public function getAllBusContacts() {
    //     $busContacts = $this->busContactsService->getAll();
    //     return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }
    public function getAllBusContacts() {
        $busContacts = $this->busContactsRepository->getAll();
        return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }
    public function createBusContacts(Request $request) {
        $data = $request->only([
            'bus_id', 'type','phone','booking_sms_send','cancel_sms_send','created_by'
          ]);
        $busContactsValidation = $this->BusContactsValidator->validate($data);
        if ($busContactsValidation->fails()) {
            $errors = $busContactsValidation->errors();
            return $this->errorResponse($errors,Response::HTTP_PARTIAL_CONTENT);
        }
        try{
            //$this->busContactsService->savePostData($data);
            $this->busContactsRepository->save($data);
        } 
        catch (Exception $e){
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_ADDED'), Response::HTTP_CREATED);
    } 

    public function updateBusContacts(Request $request, $id) {
        $data = $request->only([
            'bus_id', 'type','phone','booking_sms_send','cancel_sms_send','created_by'
        ]);
        $busContactsValidation = $this->BusContactsValidator->validate($data);

        if ($busContactsValidation->fails()) {
            $errors = $busContactsValidation->errors();
            return $this->errorResponse($errors,Response::HTTP_PARTIAL_CONTENT);
        }
        try {
           // $this->busContactsService->updatePost($data, $id);
            $this->busContactsRepository->update($data, $id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_UPDATED'), Response::HTTP_CREATED);
    }

    public function deleteBusContacts ($id) {
        $result = ['status' => 200];

        try {
            //$result['data'] = $this->busContactsService->deleteById($id);
            $result['data'] = $this->busContactsRepository->delete($id);
        } 
        catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),Response::HTTP_PARTIAL_CONTENT);
        }
        return $this->successResponse(null, Config::get('constants.RECORD_REMOVED'), Response::HTTP_ACCEPTED);
    }

    // public function getBusContacts($id) {
    //   $busContacts = $this->busContactsService->getById($id);
    //   return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    // }      
    public function getBusContacts($id) {
      $busContacts = $this->busContactsRepository->getById($id);
      return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    }  
    // public function busContactsByBusId($id) {
    //     $busContacts = $this->busContactsService->getByBusId($id);
    //     return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
    //   }  

    public function busContactsByBusId($id) {
        $busContacts = $this->busContactsRepository->getByBusId($id);
        return $this->successResponse($busContacts,Config::get('constants.RECORD_FETCHED'),Response::HTTP_OK);
      }
	     
}
