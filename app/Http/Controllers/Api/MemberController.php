<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Administration\UserRegistration;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use DB;

use App\Services\MemberService;

use App\Http\Resources\Administration\UserRegistrationResource;
use App\Http\Resources\Administration\UserResource;

use App\Http\Requests\Api\MemberManagement\RegisterMemberRequest;
use App\Http\Requests\Api\MemberManagement\UpdateMemberRegistrationStatusRequest;

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

        $userRegistration = $this->memberService->registerMember($validated, $user, false);

        if($userRegistration){

            return new UserRegistrationResource($userRegistration);

        }
        else{

            return response()->json([

                'message' => 'Error saving member registration'

            ],500);
        }

    }

    public function getMemberRegistrations(Request $request)
    {

        $user = Auth::user();

        if(!($user->can('view-member-registrations'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $memberRegistrations = UserRegistration::where('category', 'member')->orderBy('created_at', 'desc');


        if(!empty($request->status)){

            $status = $request->status;

            $memberRegistrations = $memberRegistrations->where('status', $status);
        }

        $memberRegistrations = $memberRegistrations->paginate();

        return UserRegistrationResource::collection($memberRegistrations);

    }


    public function getMemberRegistration(Request $request, $registrationReference)
    {

        $user = Auth::user();

        if(!($user->can('view-member-registrations'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $memberRegistration = UserRegistration::where('category', 'member')->where('registration_reference', $registrationReference)
        ->first();

        if($memberRegistration === null){

            return response()->json([

                'message' => 'Invalid member registration'
            ],400); 
        }

        return  new UserRegistrationResource($memberRegistration);

    }


    public function updateMemberRegistrationStatus(UpdateMemberRegistrationStatusRequest $request, $memberRegistrationRefrence)
    {

        $user =  Auth::user();

        $validated = $request->validated();

        $memberRegistration =  UserRegistration::where('registration_reference', $memberRegistrationRefrence)
        ->where('category', 'member')->first();

        if($memberRegistration === null){

            return response()->json([
                'message' => 'invalid member registration'
            ],400);
        }

        /*
            In this flow, there are specific conditions we need to validate depending on the status
            the user wishes to update to 
        */

        /*
            If the required action is to approve the account
            1. The user should have permission to approve registrations
            2. The current user registration status should be reviewed
        */

        if($validated['status'] == 'approved'){

            if(!($user->can('approve-member-registrations'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($memberRegistration->status !== 'reviewed'){

                return response()->json([
    
                    'message' => 'Registration must be reviewed before it can be approved'
                ],400);

            }
        }


        /*
            If the required action is to complete review of the account
            1. The user should have permission to review registrations
            2. The current user registration status should be pending
        */

        if($validated['status'] == 'reviewed'){

            if(!($user->can('review-member-registrations'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($memberRegistration->status !== 'pending'){

                return response()->json([
    
                    'message' => 'Registration status must be pending before it can be reviewed'
                ],400);

            }
        }

                        /*
            If the required action is to complete review of the account
            1. The user should have permission to review registrations
            2. The current user registration status should be pending
        */

        if($validated['status'] == 'rejected'){

            if(!($user->can('reject-member-registrations'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            if($memberRegistration->status === 'approved'){

                return response()->json([
    
                    'message' => 'Registration has already previously been approved. Update the User profile'
                ],400);

            }
        }

        $memberRegistration = $this->memberService->updateRegistrationStatus($memberRegistration, $validated['status'], $user);

        if($memberRegistration){

            return new UserRegistrationResource($memberRegistration);

        }
        else{

            return response()->json([

                'message' => 'Error updating member registration'

            ],500);
        }   

    }

    public function getMembers(Request $request)
    {
        $user  = Auth::user();

        if(!($user->can('view-members'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $pageSize = 10;

        if(!empty($request->page_size)){

            $pageSize = $request->page_size;
        }

        $members = User::with(['accounts.plan', 'userStatus'])->where('category', 'member')->paginate($pageSize);

        return UserResource::collection($members);

    }
}
