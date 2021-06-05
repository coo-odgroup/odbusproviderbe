<?php
namespace App\Repositories;
use App\Models\BusStoppage;
class BusStoppageRepository
{
    protected $busStoppage;
    public function __construct(BusStoppage $busStoppage)
    {
        $this->busStoppage = $busStoppage;
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
        return $this->busStoppage->where('bus_id', $busid)->get();
    }
   
    public function getModel(BusStoppage $busStoppage,$data)
    {
        $busStoppage->bus_id = $data['bus_id'];
        $busStoppage->user_id = $data['user_id'];
        $busStoppage->source_id = $data['source_id'];
        $busStoppage->destination_id = $data['destination_id'];
        $busStoppage->base_seat_fare = $data['base_seat_fare'];
        $busStoppage->base_sleeper_fare = $data['base_sleeper_fare'];
        $busStoppage->dep_time = $data['dep_time'];
        $busStoppage->arr_time = $data['arr_time'];
        $busStoppage->j_day = $data['j_day'];
        $busStoppage->start_j_days = $data['start_j_days'];
        $busStoppage->created_by = "Admin";
        $busStoppage->bus_operator_id = $data['bus_operator_id'];
        return $busStoppage;
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
        $busstoppage = $this->busStoppage->where('bus_id',$id);
        $busstoppage->delete();
        return $busstoppage;
    }

}