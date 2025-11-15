<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\AppValidator\UserContentValidator;
use App\Repositories\UserContentRepository;


class UserContentController extends Controller
{
    use ApiResponser;

    protected $userContentService;
    protected $userContentValidator;
    protected $userContentRepository;



    public function __construct(
    UserContentRepository $userContentRepository,
    UserContentValidator $userContentValidator)
    {
       
        $this->userContentValidator = $userContentValidator;
        $this->userContentRepository = $userContentRepository;
    }


    public function getAllData(Request $request)
    {

        
        $usercontent = $this->userContentRepository->getAllData($request);
        return $this->successResponse($usercontent, Config::get('constants.RECORD_FETCHED'), Response::HTTP_OK);
    }

    public function adduser(Request $request)
    {
        
        $data = $request->only([
        'name',
        'bus_operator_id',
        'email',
        'phone',
        'password'
        ]);

        $usercontent = $this->userContentValidator->validate($data);


        if ($usercontent->fails()) {
            $errors = $usercontent->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        } else {
            $response = $this->userContentService->addusercontent($request);

            if ($response == 'Phone Number Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Email Id Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } elseif ($response == 'Bus Operator Exist') {
                return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
            } else {
                return $this->successResponse($response, "USER ADDED", Response::HTTP_CREATED);
            }
        }

    }
    public function updateuser(Request $request, $id)
    {
       

        $data = $request->only([
        'name',
        'email',
        'phone',
        ]);


       
        $response = $this->userContentRepository->updateusercontent($request,$id);

        if ($response == 'Phone Number Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } elseif ($response == 'Email Id Exist') {
            return $this->errorResponse($response, Response::HTTP_PARTIAL_CONTENT);
        } else {
            return $this->successResponse($response, "USER DATA UPDATED", Response::HTTP_CREATED);
        }

    }

    public function changePassword(Request $request, $id)
    {
       

        $data = $request->only([
        'password'
        ]);
        
        $this->userContentRepository->changePassword($request,$id);
        return $this->successResponse(null, "USER PASSWORD UPDATED", Response::HTTP_CREATED);
    }


    public function changeStatus($id)
    {
        
        $usercontent = $this->userContentRepository->changeStatus($id);
        return $this->successResponse($usercontent, 'USER STATUS UPDATED', Response::HTTP_OK);

    }

    public function deleteuser($id)
    {
        
        $usercontent = $this->userContentRepository->deleteusercontent($id);
        return $this->successResponse($usercontent, 'USER DELETED', Response::HTTP_OK);

    }





}
