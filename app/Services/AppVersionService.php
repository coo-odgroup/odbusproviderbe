<?php

namespace App\Services;

use App\Models\AppVersion;
use App\Repositories\AppVersionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AppVersionService
{
    
    protected $appVersionRepository;

    
    public function __construct(AppVersionRepository $appVersionRepository)
    {
        $this->appVersionRepository = $appVersionRepository;
    }

    
    // public function deleteById($id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $post = $this->appVersionRepository->delete($id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         // Log::info($e->getMessage());

    //         throw new InvalidArgumentException('Unable to delete post data');
    //     }

    //     DB::commit();

    //     return $post;

    // }

    
    // public function getAll()
    // {
    //     return $this->appVersionRepository->getAll();
    // }

    
    // public function getById($id)
    // {
    //     return $this->appVersionRepository->getById($id);
    // }

   
    // public function updatePost($data, $id)
    // {
        

        

    //     DB::beginTransaction();

    //     try {
    //         $post = $this->appVersionRepository->update($data, $id);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         // Log::info($e->getMessage());

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
    //     /*$validator = Validator::make($data, [
    //         'info' => 'required',
    //         'name' => 'required',
    //         'mandatory' => 'required',
    //         'version' => 'required',
    //         'new_version_names' => 'required',
    //         'new_version_codes' => 'required',
    //         'allowed_days' => 'required',
    //         'has_issues' => 'required',
    //         'created_by' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         throw new InvalidArgumentException($validator->errors()->first());
    //     }*/

    //     $result = $this->appVersionRepository->save($data);

    //     return $result;
    // }

}