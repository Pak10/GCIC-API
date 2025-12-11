<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Administration\RoleResource;
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
}
