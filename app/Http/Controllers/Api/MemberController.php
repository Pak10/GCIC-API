<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use DB;

use App\Services\MemberService;

use App\Http\Resources\Administration\UserRegistrationResource;

use App\Http\Requests\Api\MemberManagement\RegisterMemberRequest;

class MemberController extends Controller
{
    
    public function __construct()
    {
        $this->memberService = new MemberService;
      
    }


    public function registerMember(RegisterMemberRequest $request)
    {

        $user = Auth::user();

        $validated = $request->validated();

        $userRegistration = $this->userService->registerMember($validated, $user, false);

        if($userRegistration){

            return new UserRegistrationResource($userRegistration);

        }
        else{

            return response()->json([

                'message' => 'Error saving member registration'

            ],400);
        }

    }
}
