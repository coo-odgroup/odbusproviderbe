<?php

namespace App\Services;

use App\Models\BusStoppage;
use App\Models\Location;
use App\Models\BusStoppageTiming;
use App\Repositories\BusStoppageRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
//use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class BusStoppageService
{
    protected $busStoppageRepository;
    protected $busStoppageTiming;
    protected $location;
    public function __construct(busStoppageRepository $busStoppageRepository, BusStoppageTiming $busStoppageTiming, Location $location)
    {
        $this->busStoppageRepository = $busStoppageRepository;
        $this->busStoppageTiming = $busStoppageTiming;
        $this->location=$location;
    }
    public function deleteById($id)
    {
        try {
            $post = $this->busStoppageRepository->delete($id);

        } catch (Exception $e) {
            Log::info($e->getMessage());
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;

    }
    public function getAll()
    {
        return $this->busStoppageRepository->getAll();
    }
    public function getById($id)
    {
        return $this->busStoppageRepository->getById($id);
    }

    public function getBusStoppagebyRoutes($source_id,$destination_id)
    {
        return $this->busStoppageRepository->getBusStoppagebyRoutes($source_id,$destination_id);
    }
    public function getBusStoppagebyBusId($busid)
    {
        $data['result']= $this->busStoppageRepository->getBusStoppagebyBusId($busid);
        $data['locations']=$this->busStoppageTiming->select('location_id')->distinct()->where('bus_id',$busid)->get();
         if($data['locations']){
            foreach($data['locations'] as $v)
            { 
                $v['location_name']=$this->location->where('id', $v->location_id)->get();
               
            }
        }
        return $data;
    }
    
    public function getBusByOperator($operator_id)
    {
        return $this->busStoppageRepository->getBusByOperator($operator_id);
    }
    public function updatePost($data, $id)
    {
        try {
            $post = $this->busStoppageRepository->update($data, $id);

        } catch (Exception $e) {
            throw new InvalidArgumentException(Config::get('constants.RECORD_NOT_FOUND'));
        }
        return $post;
    }
    public function savePostData($data)
    {
        $result = $this->busStoppageRepository->save($data);
        return $result;
    }
    public function deletebyBusId($id)
    {
         $result = $this->busStoppageRepository->deletebyBusId($id);
        return $result;
    }

    public function getbusRoutebyBusId($id)
    {
         $result = $this->busStoppageRepository->getbusRoutebyBusId($id);
        return $result;
    }
}