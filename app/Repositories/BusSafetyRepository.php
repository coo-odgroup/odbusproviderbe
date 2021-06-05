<?php

namespace App\Repositories;
use App\Models\BusSafety;
use App\Models\Bus;
use Illuminate\Support\Facades\Log;
class BusSafetyRepository 
{
    protected $safety;
    protected $bus;
    public function __construct(BusSafety $busSafety, Bus $bus)
    {
        $this->busSafety = $busSafety;
        $this->bus=$bus;
    }
    public function getAll()
    {
        return $this->busSafety->whereNotIn('status', [2])->get();
    }
    public function getById($id)
    {
        return $this->busSafety
            ->where('id', $id)
            ->get();
    }
    public function getModel($data, BusSafety $busSafety)
    {
        $busSafety->name = $data['name'];
        $busSafety->created_by = $data['created_by'];
        return $busSafety;
    }
    /**
     * Save safety
     *
     * @param $data
     * @return safety
     */
    public function save($data)
    {
        $bus=$this->bus->find($data['bus_id']);
        $safetyArray=[];
        foreach($data['safety'] as $safetyItem)
        {
            $dataItem=new $this->busSafety;
            $dataItem->safety_id=$safetyItem;
            $dataItem->created_by='Admin';
            $safetyArray[]=$dataItem;

        }
        $bus->busSafety()->saveMany($safetyArray);
        return $data;
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function update($data, $id)
    {
        $safetyRecord=$this->busSafety->where('bus_id',$id);
        $safetyRecord->delete();
        $bus=$this->bus->find($data['bus_id']);
        $safetyArray=[];
        foreach($data['safety'] as $safetyItem)
        {
            $dataItem=new $this->busSafety;
            $dataItem->safety_id=$safetyItem;
            $dataItem->created_by='Admin';
            $safetyArray[]=$dataItem;

        }
        $bus->busSafety()->saveMany($safetyArray);
        return $data;
    }
    /**
     * Update safety
     *
     * @param $data
     * @return safety
     */
    public function delete($id)
    {
        $busSafety = $this->busSafety->find($id);
        $busSafety->status = 2;
        $busSafety->update();
        return $busSafety;
    }

    public function changeStatus($data,$id)
    {
        $busSafety = $this->busSafety->find($id);
        if($busSafety->status==0){
            $busSafety->status = 1;
        }elseif($busSafety->status==1){
            $busSafety->status = 0;
        }
        $busSafety->update();
        return $busSafety;
    }

}