<?php

namespace App\Services;

use App\Repositories\ApiUserRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ApiUserService
{
    protected $apiUserRepository;
    public function __construct(ApiUserRepository $apiUserRepository)
    {
        $this->apiUserRepository = $apiUserRepository;
    }

    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function savePostData($data)
    // {        
    //     try {            
    //          return  $this->apiUserRepository->save($data);          
    //     } 
    //     catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));            
    //     }
    //     return $ApiUser;
    // }


    /**
     * Delete Data by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $busType = $this->apiUserRepository->delete($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $busType;

    }
    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll($request)
    {
        return $this->apiUserRepository->getAll($request);
    }    

   
    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->apiUserRepository->getById($id);
    }
    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update($data, $id)
    {
        try {
            $agent = $this->apiUserRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
        return $agent;
    }

    
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */
    // public function getAllApiUserData($request)
    // {
    //     return $this->apiUserRepository->getAllApiUserData($request);
    // } 
   
    // public function changeStatus($request)
    // {
    //     try {
    //         $agent = $this->apiUserRepository->changeStatus($request);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
    //     }
    //     return $agent;

    // } 

    // public function apiclientprofile($request)
    // {
    //      return $this->apiUserRepository->apiclientprofile($request);
    // }
    // public function updateapiclient($request)
    // {
    //      return $this->apiUserRepository->updateapiclient($request);
    // }  


   

}