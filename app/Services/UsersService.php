<?php

namespace App\Services;

use App\Repositories\UsersRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class UsersService
{
    protected $usersRepository;
    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }
   
    /**
     * Get all users Data
     *
     * @return String
     */
    public function login($request)
    {
        return $this->usersRepository->login($request);
    }

   

}