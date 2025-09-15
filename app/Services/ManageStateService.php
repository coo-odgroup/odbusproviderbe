<?php

namespace App\Services;

use App\Repositories\ManageStateRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ManageStateService
{
    /**
     * @var $postRepository
     */
    protected $manageStateRepository;

    /**
     * PostService constructor.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(ManageStateRepository $manageStateRepository)
    {
        $this->manageStateRepository = $manageStateRepository;
    }

    /**
     * Delete post by id.
     *
     * @param $id
     * @return String
     */
    // public function statelist(){
    // 	 return $this->manageStateRepository->statelist();
    // } 

    // public function getAllstate($request){
    // 	 return $this->manageStateRepository->getAllstate($request);
    // }

    // public function createState($request){
    // 	 return $this->manageStateRepository->createState($request);
    // }

    // public function changeStatus($id){
    // 	 return $this->manageStateRepository->changeStatus($id);
    // }

    // public function updateState($data, $id){
    //         return $location = $this->manageStateRepository->updateState($data, $id);
    // }
}