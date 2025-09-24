<?php

namespace App\Services;

use App\Models\Banner;
use App\Repositories\BannerRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class BannerService
{
   
    protected $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    // public function getAllBanner()
    // {
    //     return $this->bannerRepository->getAllBanner();
    // }
    
    // public function getData($request)
    // {
    //     return $this->bannerRepository->getData($request);
    // }
    // public function deleteById($id)
    // {
    //     try {
    //         $banner = $this->bannerRepository->delete($id);
    //     } catch (Exception $e) {
    //         // Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $banner;
    // }

    // public function getById($id)
    // {
    //     return $this->bannerRepository->getById($id);
    // }
    // public function save($data)
    // {   
    //     try {
    //         $banner = $this->bannerRepository->save($data);
    //     } catch (Exception $e) {
    //         // Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
    //     }
    //     return $banner;
    // }
    // public function update($data)
    // {
    //     try {
    //         $banner = $this->bannerRepository->update($data);
    //     } catch (Exception $e) {
    //         // Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
    //     }
    //     return $banner;
    // }
    // public function changeStatus($id)
    // {
    //     try {
    //         $banner = $this->bannerRepository->changeStatus($id);
    //     } catch (Exception $e) {
    //         // Log::info($e->getMessage());
    //         throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
    //     }
    //     return $banner;
    // }

    
}