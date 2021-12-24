<?php

namespace App\Repositories;

use App\Models\BusStoppageTiming;
use App\Models\BusLocationSequence;
use App\Models\Location;
use Illuminate\Support\Facades\Log;
class BusStoppageTimingRepository
{
    
    protected $busStoppageTiming;
    protected $busLocationSequence;
    protected $location;

    
    public function __construct(BusStoppageTiming $busStoppageTiming, Location $location, BusLocationSequence $busLocationSequence)
    {
        $this->busLocationSequence = $busLocationSequence;
        $this->busStoppageTiming = $busStoppageTiming;
        $this->location = $location;
    }

    
    public function getAll()
    {
        return $this->busStoppageTiming->get();
    }

    
    public function getById($id)
    {
        return $this->busStoppageTiming->where('id', $id)->get();
    }
    public function busStoppageTimingbyBusId($bus_id)
    {
        $result=[];
        $result['stoppage_timing']=$this->busStoppageTiming->where('bus_id', $bus_id)->where('status','1')->get();

        $result['routes']=$this->busLocationSequence->where('status','1')->select('location_id')->orderBy('sequence')->where('bus_id', $bus_id)->get();
        $result['sequence']=[];
        foreach($result['routes'] as $routeInfo)
        {
            $result['sequence'][]=$this->busLocationSequence->where('bus_id',$bus_id)->where('location_id',$routeInfo->location_id)->where('status','1')->get();
        }

        //Log::info($result);

        //return $this->location->with('busStoppageTiming')->get();
        return $result;
    }
    public function busStoppageTimingbyBusIdClone($bus_id)
    {
        $result=[];
        $result['stoppage_timing']=$this->busStoppageTiming->where('bus_id', $bus_id)->get();

        $result['routes']=$this->busLocationSequence->select('location_id')->orderBy('sequence','DESC')->where('bus_id', $bus_id)->get();
        $result['sequence']=[];
        foreach($result['routes'] as $routeInfo)
        {
            $result['sequence'][]=$this->busLocationSequence->where('bus_id',$bus_id)->where('location_id',$routeInfo->location_id)->get();
        }

        //Log::info($result);

        //return $this->location->with('busStoppageTiming')->get();
        return $result;
    }
    public function getModel(BusStoppageTiming $busstoppageTiming,$data)
    {
        $busstoppageTiming->bus_id = $data['bus_id'];
        $busstoppageTiming->stoppage_name = $data['stoppage_name'];
        $busstoppageTiming->boarding_droping_id = $data['boarding_droping_id'];
        $busstoppageTiming->stoppage_time = $data['stoppage_time'];
        $busstoppageTiming->created_by = "Admin";
        $busstoppageTiming->location_id = $data['location_id'];
        return $busstoppageTiming;
    }
    public function save($data)
    {
        $busstoppageTiming = new $this->busStoppageTiming;
        $busstoppageTiming=$this->getModel($busstoppageTiming,$data);
        $busstoppageTiming->save();
        return $busstoppageTiming;
    }
    public function update($data, $id)
    {
        $busstoppageTiming = $this->busStoppageTiming->find($id);
        $busstoppageTiming=$this->getModel($busstoppageTiming,$data);
        $busstoppageTiming->update();
        return $busstoppageTiming;
    }
    public function delete($id)
    {
        
        $busstoppageTiming = $this->busStoppageTiming->find($id);
        $busstoppageTiming->delete();
        return $busstoppageTiming;
    }
    
    public function deleteByStoppageId($id)
    {
        //REMOVE FROM TABLE bus_stoppage_timing Instead of Change Status to 2
        $busstoppageTiming = $this->busStoppageTiming->where('bus_id',$id);
        $busstoppageTiming->delete();
        return $busstoppageTiming;
    }
}