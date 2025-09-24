<?php

namespace App\Services;

use App\Models\BusSitting;
use App\Repositories\RoleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator; 
use InvalidArgumentException;

class RoleService
{
    
    protected $roleRepository;

    /**
     * BusSittingService constructor.
     *
     * @param BusSittingRepository $busSittingRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Delete Data by id.
     *
     * @param $id
     * @return String
     */
    // public function deleteById($id)
    // {
    //     try {
    //         $role = $this->roleRepository->delete($id);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $role;
    // }

    /**
     * Get all Data
     *
     * @return String
     */
    // public function getAll()
    // {
    //     return $this->roleRepository->getAll();
    // }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    // public function getById($id)
    // {
    //     return $this->roleRepository->getById($id);
    // }

    /**
     * Update  data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    // public function update($data, $id)
    // {
    //     try {
    //         $role = $this->roleRepository->update($data, $id);
    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //     }
    //     return $role;

    // }

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
    //         $post = $this->roleRepository->save($data);

    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //     }
    //     return $post;
    // }
    /**
     * Get all Data in Datatable Format.
     *
     * @return String
     */

    // public function getAllRoleDT($request)
    // {
    //     return $this->roleRepository->getAllBusSittingDT($request);
    // } 

    // public function RoleData($request)
    // {
    //     return $this->roleRepository->RoleData($request);
    // }
    // public function changeStatus($id)
    // {
    //     try {
    //         $post = $this->roleRepository->changeStatus($id);

    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException('Unable to change status');
    //     }
    //     return $post;
    // }
}