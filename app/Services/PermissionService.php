<?php

namespace App\Services;

use App\Repositories\PermissionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator; 
use InvalidArgumentException;

class PermissionService
{
    
    protected $permissionRepository;

    /**
     * PermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Delete Data by id.
     *
     * @param $id
     * @return String
     */


    public function deleteById($id)
    {
        try {
            $role = $this->permissionRepository->delete($id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $role;
    }

    /**
     * Get all Data
     *
     * @return String
     */
    public function getAll()
    {
        return $this->permissionRepository->getAll();
    }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->permissionRepository->getById($id);
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
            $role = $this->permissionRepository->update($data, $id);
        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $role;

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
        try {
            $post = $this->permissionRepository->save($data);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $post;
    }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */

    public function getAllPermissionDT($request)
    {
        return $this->permissionRepository->getAllPermissionDT($request);
    } 

    public function PermissionData($request)
    {
        return $this->permissionRepository->PermissionData($request);
    }
    public function changeStatus($id)
    {
        try {
            $post = $this->permissionRepository->changeStatus($id);

        } catch (Exception $e) {
            throw new InvalidArgumentException('Unable to change status');
        }
        return $post;
    }
}