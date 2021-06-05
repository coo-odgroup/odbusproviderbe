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
        return $this->busGallery->whereNotIn('status', [2])->get();
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
    public function save($data)
    {
        $uploadFolder = 'busGallery';
        $image = $data->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );


        $busGallery = new $this->busGallery; 
        
        $busGallery->bus_id = $data["bus_id"];
        $busGallery->alt_tag = $data["alt_tag"];
        $busGallery->created_by = "Admin";
        $busGallery->image=$uploadedImageResponse['image_url'];

        $busGallery->save();

        return $busGallery->fresh();
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