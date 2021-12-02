<?php

namespace App\Services;

use App\Models\AppDownload;
use App\Repositories\AppDownloadRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class AppDownloadService
{
    /**
     * @var $appDownloadRepository
     */
    protected $appDownloadRepository;

    /**
     * AppDownloadService constructor.
     *
     * @param AppDownloadRepository $appDownloadRepository
     */
    public function __construct(AppDownloadRepository $appDownloadRepository)
    {
        $this->appDownloadRepository = $appDownloadRepository;
    }

    /**
     * Delete  by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        try {
            $post = $this->appDownloadRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            // Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }

    /**
     * Get all Data.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->appDownloadRepository->getAll();
    }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->appDownloadRepository->getById($id);
    }

    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updatePost($data, $id)
    {
        try {
            $post = $this->appDownloadRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            // Log::info($e->getMessage());

            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }

    /**
     * Validate  data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function savePostData($data)
    {
        

        $result = $this->appDownloadRepository->save($data);

        return $result;
    }

}