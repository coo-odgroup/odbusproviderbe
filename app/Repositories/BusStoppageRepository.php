<?php
namespace App\Repositories;
use App\Models\BusStoppage;
use App\Models\Location;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Log;


class BusStoppageRepository
{
    protected $busStoppage;
    public function __construct(BusStoppage $busStoppage,Location $location)
    {
        $this->busStoppage = $busStoppage;
        $this->location = $location; 
    }
    public function getAll()
    {
        return $this->busStoppage->get();
    }
    public function getById($id)
    {
        return $this->busStoppage ->where('id', $id)->get();
    }
    public function getBusByOperator($operatorId)
    {
        return $this->busStoppage>with('bus')->where('bus_operator_id', $operatorId)->get();
    }
    public function getBusStoppagebyRoutes($sourceId,$destinationId)
    {
        return $this->busStoppage->with('bus')->where('source_id', $sourceId)->where('destination_id',$destinationId)->get();
    }
    public function getBusStoppagebyBusId($busid)
    {
        return $this->busStoppage->where('bus_id', $busid)->where('status','!=',2)->get();
    } 

    public function getbusRoutebyBusId($busid)
    {
        $data = $this->busStoppage->where('bus_id', $busid)->where('status','!=',2)->get();
        foreach ($data as  $a) 
        {                          
            $a['source']=$this->location->where('id', $a->source_id)->get();
            $a['destination']=$this->location->where('id', $a->destination_id)->get(); 
        }

        return $data
;
    }
   
    public function getModel(BusStoppage $busStoppage,$data)
    {
        $dt0=date('Y-m-d')." ".$data['arr_time'];
        $dt1=date('Y-m-d')." ".$data['dep_time'];

        $start_journey_day=$data['start_j_days']-1;
        $end_journey_day=$data['j_day']-1;

        $day0 = date('Y-m-d H:i:s',strtotime('+'.$start_journey_day.' days',strtotime($dt0))); //DEP TIME
        $day1 = date('Y-m-d H:i:s',strtotime('+'.$end_journey_day.' days',strtotime($dt1))); //ARR TIME

        $busStoppage->bus_id = $data['bus_id'];
        $busStoppage->user_id = $data['user_id'];
        $busStoppage->source_id = $data['source_id'];
        $busStoppage->destination_id = $data['destination_id'];
        $busStoppage->base_seat_fare = $data['base_seat_fare'];
        $busStoppage->base_sleeper_fare = $data['base_sleeper_fare'];
        $busStoppage->dep_time = $day0;
        $busStoppage->arr_time = $day1;
        $busStoppage->j_day = $data['j_day'];
        $busStoppage->start_j_days = $data['start_j_days'];
        $busStoppage->created_by = "Admin";
        $busStoppage->bus_operator_id = $data['bus_operator_id'];
        $busStoppage->seize_booking_minute = $data['seize_booking_minute'];
        $busStoppage->status = $data['status'];
        
        return $busStoppage;
    }
    public function checkDuplicate($data)
    {
        return $this->busStoppage->where('bus_id', $data['bus_id'])
                                  ->where('source_id', $data['source_id'])
                                  ->where('destination_id', $data['destination_id'])
                                  ->get();
    }
    public function save($data)
    {
        $busStoppage = new $this->busStoppage;
        $busStoppage = $this->getModel($busStoppage,$data);
        $busStoppage->save();
        return $busStoppage->id;
    }

    
    public function update($data, $id)
    {
        
        $busStoppage = $this->busStoppage->find($id);
        $busStoppage = $this->getModel($busStoppage,$data);
        $busStoppage->update();
        return $busStoppage;
    }
    public function delete($id)
    {
        $busstoppage = $this->busStoppage->find($id);
        $busstoppage->delete();
        return $busstoppage;
    }
    public function deletebyBusId($id)
    {
        $busstoppage = $this->busStoppage->where('bus_id',$id)->update(array("status"=>"2"));
        //$busstoppage->delete();
        return $busstoppage;
    }

    public function updateStatus($id)
    {
        $busstoppage = $this->busStoppage->where('id',$id)->update(array("status"=>"2"));
        //$busstoppage->delete();
        return $busstoppage;
    }

    

}