<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SocialMediaRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\SocialMediaValidator;

class SocialMediaController extends Controller
{
    use ApiResponser;

    protected $socialmediaRepository;
    protected $socialmediaValidator;

    public function __construct(SocialMediaRepository $socialmediaRepository, SocialMediaValidator $socialmediaValidator)
    {
        $this->socialmediaRepository = $socialmediaRepository;
        $this->socialmediaValidator = $socialmediaValidator;
    }

    public function getAllsocialmedia(Request $request)
    {


        $socialmedia = $this->socialmediaRepository->getAll($request);
        return $this->successResponse($socialmedia, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function addsocialmedia(Request $request)
    {
        $data = $request->only([
            'user_id',
            'facebook_link',
            'twitter_link',
            'instagram_link',
            'googleplus_link',
            'linkedin_link',
            'created_by',
        ]);

        $socialmedia = $this->socialmediaValidator->validate($data);


        if ($socialmedia->fails()) {
            $errors = $socialmedia->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response =  $this->socialmediaRepository->addsocialMedia($request);


            if ($response == 'User Social Media Data already exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Social Media Added", Response::HTTP_CREATED);
            }
        }
    }


    public function updatesocialmedia(Request $request, $id)
    {

        $data = $request->only([
            'user_id',
            'facebook_link',
            'twitter_link',
            'instagram_link',
            'googleplus_link',
            'linkedin_link',
            'created_by',
        ]);

        $socialmedia = $this->socialmediaValidator->validate($data);


        if ($socialmedia->fails()) {
            $errors = $socialmedia->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response =  $this->socialmediaRepository->updatesocialMedia($request, $id);


            if ($response == 'User Social Media Data already exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "Social Media Added", Response::HTTP_CREATED);
            }
        }
    }

    public function deletesocialmedia($id)
    {

        $socialmedia = $this->socialmediaRepository->deletesocialMedia($id);
        return $this->successResponse($socialmedia, "Social Media Deleted", Response::HTTP_OK);
    }
    public function changeStatus($id)
    {
        $socialmedia = $this->socialmediaRepository->changeStatus($id);
        return $this->successResponse($socialmedia, "Social Media Status Updated", Response::HTTP_OK);
    }
}
