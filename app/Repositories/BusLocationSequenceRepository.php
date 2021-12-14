<?php

namespace App\Repositories;
use App\Models\BusLocationSequence;
use Illuminate\Support\Facades\Log;
class BusLocationSequenceRepository 
{
    protected $busLocationSequence;
    public function __construct(BusLocationSequence $busLocationSequence)
    {
        $this->busLocationSequence = $busLocationSequence;
    }
    public function getAll()
    {
        return $this->busLocationSequence->whereNotIn('status', [2])->get();
    }
    
    public function getModel($data, BusLocationSequence $busLocationSequence)
    {
        $busLocationSequence->bus_id = $data['bus_id'];
        $busLocationSequence->location_id = $data['location_id'];
        $busLocationSequence->sequence = $data['sequence'];
        return $busLocationSequence;
    }
    /**
     * Save busLocationSequence
     *
     * @param $data
     * @return busLocationSequence
     */
    public function save($data)
    {
        $busLocationSequence = new $this->busLocationSequence;
        $busLocationSequence=$this->getModel($data,$busLocationSequence);
        $busLocationSequence->save();
        return $busLocationSequence;
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function update($data, $id)
    {
        $busLocationSequence = $this->busLocationSequence->find($id);
        $busLocationSequence=$this->getModel($data,$busLocationSequence);
        $busLocationSequence->update();
        return $busLocationSequence;
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function delete($id)
    {
        $safety = $this->safety->find($id);
        $safety->status = 2;
        $safety->update();
        return $safety;
    }

    public function deletebyBusId($id)
    {
        $sequence = $this->busLocationSequence->where('bus_id',$id)->update(array("status"=>"2"));
        //$busstoppage->delete();
        return $sequence;
    }

}