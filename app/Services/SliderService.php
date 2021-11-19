<?php

namespace App\Services;

use App\Models\Slider;
use App\Repositories\SliderRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class SliderService
{
   
    protected $sliderRepository;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    public function getAllSlider()
    {
        return $this->sliderRepository->getAllSlider();
    }
    
    public function getData($request)
    {
        return $this->sliderRepository->getData($request);
    }
    public function deleteById($id)
    {
        try {
            $slider = $this->sliderRepository->delete($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $slider;
    }

    public function getById($id)
    {
        return $this->sliderRepository->getById($id);
    }
    public function save($data)
    {   
        try {
            $slider = $this->sliderRepository->save($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.INVALID_ARGUMENT_PASSED'));
        }
        return $slider;
    }
    public function update($data)
    {
        try {
            $slider = $this->sliderRepository->update($data);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $slider;
    }
    public function changeStatus($id)
    {
        try {
            $slider = $this->sliderRepository->changeStatus($id);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.UNABLE_CHANGE_STATUS'));
        }
        return $slider;
    }

    
}