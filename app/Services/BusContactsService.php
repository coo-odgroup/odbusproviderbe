<?php

namespace App\Services;

use App\Models\BusContacts;
use App\Repositories\BusContactsRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusContactsService
{
    
    protected $busContactsRepository;

    
    public function __construct(busContactsRepository $busContactsRepository)
    {
        $this->busContactsRepository = $busContactsRepository;
    }

    public function deleteByBusId($id)
    {
        $post = $this->busContactsRepository->deletebyBusid($id);
    }
    // public function deleteById($id)
    // {
    //     try {
    //         $post = $this->busContactsRepository->delete($id);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $post;
    // }
    // public function getAll()
    // {
    //     return $this->busContactsRepository->getAll();
    // }
    // public function getById($id)
    // {
    //     return $this->busContactsRepository->getById($id);
    // }
    // public function getByBusId($id)
    // {
    //     return $this->busContactsRepository->getByBusId($id);
    // }
    // public function updatePost($data, $id)
    // {
    //     try {
    //         $post = $this->busContactsRepository->update($data, $id);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $post;

    // }
    // public function savePostData($data)
    // {
    //     $result = $this->busContactsRepository->save($data);
    //     return $result;
    // }

}