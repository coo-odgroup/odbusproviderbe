<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusGallery;
use App\Services\BusGalleryService;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Storage;

use Exception;

class BusGalleryController extends Controller
{


    /**
     * @var busGalleryService
     */
    protected $busGalleryService;

    /**
     * PostController Constructor
     *
     * @param BusGalleryService $busGalleryService
     *
     */
    public function __construct(BusGalleryService $busGalleryService)
    {
        $this->busGalleryService = $busGalleryService;
    }
    public function getAllBusGallery() {
        $busGallery = $this->busGalleryService->getAll();;
        $output ['status']=1;
        $output ['message']='All Data Fetched Successfully';
        $output ['result']=$busGallery;
        return response($output, 200);
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            // return sendCustomResponse($validator->messages()->first(),  'error', 500);
            $errors = $validator->errors();
            return $errors->toJson();
        }
        // $uploadFolder = 'busGallery';
        // $image = $request->file('image');
        // $image_uploaded_path = $image->store($uploadFolder, 'public');
        // $uploadedImageResponse = array(
        //     "image_name" => basename($image_uploaded_path),
        //     "image_url" => Storage::disk('public')->url($image_uploaded_path),
        //     "mime" => $image->getClientMimeType()
        // );


        // $busGallery = new BusGallery; 
        
        // $busGallery->bus_id = $request->bus_id;
        // $busGallery->alt_tag = $request->alt_tag;
        // $busGallery->created_by = "Admin";
        // $busGallery->image=$uploadedImageResponse['image_url'];

        // $busGallery->save();
        $this->busGalleryService->savePostData($request);
        return response()->json([
            "status"=> 1,
            "message" => "New Bus Gallery added Successfully"
        ], 201);





        // return sendCustomResponse('File Uploaded Successfully', 'success',   200, $uploadedImageResponse);
    }

    public function deleteBusGallery ($id) {
        $this->busGalleryService->deleteById($id);
        $output ['status']=1;
        $output ['message']='Data Deleted successfully';
        return response($output, 200);
    }
  
    public function getBusGallery($id) {
        $ame= $this->busGalleryService->getById($id);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }
    public function getBusGalleryBus($bid) {
        $ame= $this->busGalleryService->getByBusId($bid);
        $output ['status']=1;
        $output ['message']='Single Data Fetched Successfully';
        $output ['result']=$ame;
        return response($output, 200);
    }






}
