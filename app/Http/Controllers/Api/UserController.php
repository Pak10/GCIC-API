<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use DB;

use App\Services\UserService;

use App\Http\Resources\Administration\UserRegistrationResource;

use App\Http\Requests\Api\UserManagement\RegisterUserRequest;

class UserController extends Controller
{

    public function __construct()
    {
        $this->userService = new UserService;
      
    }
}
