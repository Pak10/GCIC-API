<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Administration\RoleResource;
use App\Http\Resources\Administration\PermissionResource;
use App\Services\AuthorisationService;
use App\Http\Requests\Api\Authorisation\AttachPermissionsRequest;
use App\Http\Requests\Api\Authorisation\CreateRoleRequest;
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


    public function getRole(Request $request, $roleId)
    {

        $user = Auth::user();

        if(!($user->can('view-roles'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }


        $role = Role::with('permissions')->where('id', $roleId)->first();

        if($role === null){

            return response()->json([

                'message' => 'Invalid role provided'
            ],400);
        }

        return new RoleResource($role);

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

    public function attachPermissions(AttachPermissionsRequest $request, $roleId)
    {
        $user =  Auth::user();

        $validated = $request->validated();

        $role = Role::where('id', $roleId)->first();

        if($role === null){

            return response()->json([

                'message' => 'Invalid role provided'
            ],400);
        }

        $role =  $this->authorisationService->assignPermissions($role, $validated['permissions']);

        if($role){

            return new RoleResource($role);
        }
        else{

            return response()->json([

                'message' => 'Error attaching permissions'
            ],500);
        }

    }
}
