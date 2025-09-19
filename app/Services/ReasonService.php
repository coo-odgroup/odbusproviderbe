<?php

namespace App\Services;

use App\Models\Reason;
use App\Repositories\ReasonRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ReasonService
{
    
    protected $reasonRepository;

    
    public function __construct(reasonRepository $reasonRepository)
    {
        $this->reasonRepository = $reasonRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->reasonRepository->delete($id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to delete post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    
    // public function getAll()
    // {
    //     return $this->reasonRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->reasonRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->reasonRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to update post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function savePostData($data)
    // {
        

    //     $result = $this->reasonRepository->save($data);

    //     return $result;
    // }

}