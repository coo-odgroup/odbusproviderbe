<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use App\Models\BusDisplayInfo;
use Illuminate\Support\Facades\Config;

class DisplayInfoRepository

{
    /**
     * @var BusDisplayInfo
     */
    protected $displayinfo;

    public function __construct(BusDisplayInfo $displayinfo)
    {
        $this->displayinfo = $displayinfo;
    }

   
    public function getAll()
    {
        return $this->displayinfo->get();

    }

    public function getModel($data, BusDisplayInfo $displayinfo)
    {
        
        $displayinfo->name = $data['name'];
        return $displayinfo;
    }

    public function getById($id)
    {
        return $this->displayinfo->where('id', $id)->get();
    }

    public function save($data)
    {

        $displayinfo = new $this->displayinfo();
        $displayinfo = $this->getModel($data, $displayinfo);
        $displayinfo->save();
        return $displayinfo;
    }

  
    public function update($data, $id)
    {
        $displayinfo = $this->displayinfo->find($id);
        $displayinfo = $this->getModel($data, $displayinfo);
        $displayinfo->update();
        return $displayinfo;
    }

    public function delete($id)
    {
        $post = BusDisplayInfo::find($id);
        $post->delete();
        return 'success';

    }


}
