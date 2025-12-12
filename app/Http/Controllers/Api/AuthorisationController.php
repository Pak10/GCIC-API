<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Administration\RoleResource;
use App\Http\Resources\Administration\PermissionResource;
use App\Services\AuthorisationService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use Mail;
use DB;

class AuthorisationController extends Controller
{

    public function __construct()
    {
        $this->authorisationService = new AuthorisationService;
      
    }
    

    public function getRoles(Request $request)
    {

        $user = Auth::user();

        if(!($user->can('view-roles'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }


        $roles = Role::orderBy('created_at', 'desc')->get();

        return RoleResource::collection($roles);

    }


    public function getPermissions(Request $request)
    {

        $user = Auth::user();

        if(!($user->can('view-permissions'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }


        $permissions = Permission::orderBy('created_at', 'desc')->get();

        return PermissionResource::collection($permissions);

    }


    public function createRole(CreateRoleRequest $request)
    {
        $user =  Auth::user();

        $validated = $request->validated();

        $role =  $this->authorisationService->storeRole($validated);

        if($role){

            return new RoleResource($role);
        }
        else{

            return response()->json([

                'message' => 'Error Storing role'
            ],500);
        }


    }
}
