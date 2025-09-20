<?php

namespace App\Services;

use App\Repositories\CouponTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator; 
use InvalidArgumentException;

class CouponTypeService
{
    
    protected $couponTypeRepository;

    /**
     * CouponTypeService constructor.
     *
     * @param CouponTypeRepository $couponTypeRepository
     */
    public function __construct(CouponTypeRepository $couponTypeRepository)
    {
        $this->couponTypeRepository = $couponTypeRepository;
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
    //         $role = $this->couponTypeRepository->delete($id);
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
    //     return $this->couponTypeRepository->getAll();
    // }

    /**
     * Get  by id.
     *
     * @param $id
     * @return String
     */
    // public function getById($id)
    // {
    //     return $this->couponTypeRepository->getById($id);
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
    //         $role = $this->couponTypeRepository->update($data, $id);
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
    //         $post = $this->couponTypeRepository->save($data);

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

    // public function getCouponTypeDT($request)
    // {
    //     return $this->couponTypeRepository->getCouponTypeDT($request);
    // } 

    // public function CouponTypeData($request)
    // {
    //     return $this->couponTypeRepository->CouponTypeData($request);
    // }
    // public function changeStatus($id)
    // {
    //     try {
    //         $post = $this->couponTypeRepository->changeStatus($id);

    //     } catch (Exception $e) {
    //         throw new InvalidArgumentException('Unable to change status');
    //     }
    //     return $post;
    // }
}