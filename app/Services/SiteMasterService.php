<?php

namespace App\Services;

use App\Models\SiteMaster;
use App\Repositories\SiteMasterRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class SiteMasterService
{
    
    protected $siteMasterRepository;

    
    public function __construct(SiteMasterRepository $siteMasterRepository)
    {
        $this->siteMasterRepository = $siteMasterRepository;
    }

    
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $post = $this->siteMasterRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $post;

    }

    
    public function getAll()
    {
        return $this->siteMasterRepository->getAll();
    }

    
    public function getById($id)
    {
        return $this->siteMasterRepository->getById($id);
    }

   
    public function updatePost($data, $id)
    {
        

        

        DB::beginTransaction();

        try {
            $post = $this->siteMasterRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update post data');
        }

        DB::commit();

        return $post;

    }

    /**
     * Validate post data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {
        
        $result = $this->siteMasterRepository->save($data);

        return $result;
    }

}