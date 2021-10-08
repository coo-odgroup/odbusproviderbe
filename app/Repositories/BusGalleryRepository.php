<?php

namespace App\Repositories;

use App\Models\BusGallery;
use Storage;


class BusGalleryRepository
{
    /**
     * @var BusGallery
     */
    protected $busGallery;

    /**
     * BusGalleryRepository constructor.
     *
     * @param BusGallery $busGallery
     */
    public function __construct(BusGallery $busGallery)
    {
        $this->busGallery = $busGallery;
    }

    /**
     * Get all busGallery.
     *
     * @return BusGallery $busGallery
     */
    public function getAll()
    {
        return $this->busGallery
                    ->with('bus')
                    ->whereNotIn('status', [2])
                    ->get();
    }

    /**
     * Get busGallery by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->busGallery
            ->where('id', $id)
            ->get();
    }
    public function getByBusId($bid)
    {
        return $this->busGallery->whereNotIn('status', [2])
            ->where('bus_id', $bid)
            ->get();
    }

    /**
     * Save busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function getModel($data, BusGallery $busGallery)
    {
        $busGallery->bus_id = $data['bus_id'];
        $busGallery->image = $data['icon'];
        $busGallery->created_by = $data['created_by'];
        return $busGallery;
    }

    public function save($data)
    {

        $busGallery = new $this->busGallery;
        $busGallery=$this->getModel($data,$busGallery);
        $busGallery->save();
        return $busGallery;
    }

    /**
     * Update busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function update($data, $id)
    {
        
        // $post = $this->busGallery->find($id);

        // $post->mobileno = $data['mobileno'];

        // $post->update();

        // return $post;
    }

    /**
     * Update busGallery
     *
     * @param $data
     * @return BusGallery
     */
    public function delete($id)
    {
        
        $post = $this->busGallery->find($id);
        $post->status = 2;
        $post->update();
        return $post;
    }

}