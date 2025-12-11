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
use App\Http\Resources\Administration\UserResource;
use App\Http\Resources\Administration\BasicUserResource;

use App\Http\Requests\Api\UserManagement\RegisterUserRequest;

class UserController extends Controller
{

    public function __construct()
    {
        $this->userService = new UserService;
      
    }

    public function registerUser(RegisterUserRequest $request)
    {
        $user =  Auth::user();

        $validated =  $request->validated();

        $user  =  $this->userService->storeUser($validated, $user, false);

        if($user){

            return new UserResource($user);

        }
        else{

            return response()->json([

                'message' => 'Error saving the user'
            ],500);

        }
    }


    public function getUsers(Request $request)
    {
        $user =  Auth::user();
 
        if(!($user->can('view-users'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $users = User::with('roles', 'userStatus')->where('category', 'administrator')->orderBy('created_at', 'desc');

        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $users = $users->paginate($pageSize);

        return BasicUserResource::collection($users);

    }


    public function getUser(Request $request, $userId)
    {
        $user =  Auth::user();
 
        if(!($user->can('view-users'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $user = User::with('roles', 'userStatus', 'accounts', 'details')->where('category', 'administrator')
        ->where('id', $userId)->first();
    
        if($user === null){

            return response()->json([

                'message' => 'Invalid user'
            ], 400);
        }

        return new UserResource($user);

    }
}
