<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\AppValidator\LoginValidator;
//use App\AppValidator\ChangePasswordValidator;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class UserController extends Controller
{
    use ApiResponser;

    protected $userService;
    protected $userRepository;
    protected $loginValidator;
    //protected $changePasswordValidator;

    public function __construct(
        UserService $userService,
        UserRepository $userRepository,
        LoginValidator $loginValidator
        //ChangePasswordValidator $changePasswordValidator
    )
     {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->loginValidator = $loginValidator;
       // $this->changePasswordValidator = $changePasswordValidator;
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        $arrParam = json_decode(decryptRequest($request['REQUEST_DATA']));
        $data = [];

        $data['email'] = isset($arrParam->email) ? $arrParam->email : null;
        $data['password'] = isset($arrParam->password) ? $arrParam->password : null;
        $data['user_type'] = isset($arrParam->user_type) ? $arrParam->user_type : null;

        $loginValidation = $this->loginValidator->validate($data);

        if ($loginValidation->fails()) {
            $errors = $loginValidation->errors();
            return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        }

        try {
            $response = $this->userRepository->login($data);
            switch ($response) {
                case ('un_registered_agent'):
                    return $this->errorResponse(Config::get('constants.UNREGISTERED'), Response::HTTP_OK);
                case ('pwd_mismatch'):
                    return $this->errorResponse(Config::get('constants.PWD_MISMATCH'), Response::HTTP_OK);
                case ('agent_role_mismatch'):
                    return $this->errorResponse(Config::get('constants.ROLE_MISMATCH'), Response::HTTP_OK);
                case ('inactive_user'):
                    return $this->errorResponse(Config::get('constants.INACTIVE_USER'), Response::HTTP_OK);
            }

            return $this->successResponse(
                encryptResponse($response),
                Config::get('constants.LOGIN_SUCCESSFUL'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        $arrParam = json_decode(decryptRequest($request['REQUEST_DATA']));
        $data = [];

        $data['user_id'] = isset($arrParam->user_id) ? $arrParam->user_id : null;
        $data['old_password'] = isset($arrParam->old_password) ? $arrParam->old_password : null;
        $data['new_password'] = isset($arrParam->new_password) ? $arrParam->new_password : null;

        // $changePasswordValidation = $this->changePasswordValidator->validate($data);

        // if ($changePasswordValidation->fails()) {
        //     $errors = $changePasswordValidation->errors();
        //     return $this->errorResponse($errors->toJson(), Response::HTTP_PARTIAL_CONTENT);
        //}

        try {
            $response = $this->userRepository->changePassword($data);

            if ($response === 'pwd_mismatch') {
                return $this->errorResponse(Config::get('constants.PWD_MISMATCH'), Response::HTTP_OK);
            }

            return $this->successResponse(
                encryptResponse($response),
                Config::get('constants.PASSWORD_CHANGED'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    /**
     * Get all users
     */
    public function getAllUsers(Request $request)
    {
        try {
            $response = $this->userRepository->getAllUsers();
            return $this->successResponse(
                encryptResponse($response),
                Config::get('constants.DATA_RETRIEVED'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    /**
     * Get user details by ID
     */
    public function getUserById($id)
    {
        try {
            $response = $this->userRepository->getUserById($id);
            if (!$response) {
                return $this->errorResponse(Config::get('constants.NOT_FOUND'), Response::HTTP_NOT_FOUND);
            }

            return $this->successResponse(
                encryptResponse($response),
                Config::get('constants.DATA_RETRIEVED'),
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }

    /**
     * Update user details
     */
    public function updateUser(Request $request, $id)
    {
        $arrParam = json_decode(decryptRequest($request['REQUEST_DATA']));
        $data = [];

        $data['name'] = isset($arrParam->name) ? $arrParam->name : null;
        $data['email'] = isset($arrParam->email) ? $arrParam->email : null;
        $data['phone'] = isset($arrParam->phone) ? $arrParam->phone : null;

        try {
            $this->userRepository->updateUser($data, $id);
            return $this->successResponse($data, Config::get('constants.USER_UPDATED'), Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_PARTIAL_CONTENT);
        }
    }
}
