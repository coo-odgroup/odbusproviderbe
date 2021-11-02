<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class UsersRepository
{
    /**
     * @var User
     */
    protected $user;

    /**
     * BusTypeRepository constructor.
     *
     * @param Post $User
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    
    public function login($request)
    {

        $email = $request['email'];
        $password = $request['password'];
        return $this->user->with('role')->where('email', $email)->where('password', $password)->get();
    }

    
    
}