<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Log;
use DB;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\Administration\Account;
use App\Models\Administration\UserRegistration;
use App\Models\Transactions\AccountTransaction;
use App\Models\Transactions\TransactionType;

class ReportController extends Controller
{
    

    public function getMemberStatistics(Request $request)
    {

        $user =  Auth::user();

        $showAdminReports = false;

        if($user->category === 'member'){

            $accounts = Account::where('user_id', $user->id)->pluck('id');

        }
        else{

            if(!($user->can('member-reports'))){

                return response()->json([
    
                    'message' => 'User does not have access to this resource'
                ],403);
            }

            $showAdminReports = true;

        }


        if($showAdminReports === true){

        
            $activeUserStatus = $status =  UserStatus::select('id', 'status')->where('status', 'Active')->first();
            $totalActiveMembers =  User::where('category', 'member')->where('user_status_id', $activeUserStatus->id)
            ->count();
        }

        if($showAdminReports === true){

            $pendingRegistrations =  UserRegistration::where('status', 'pending')->count();

        }

        if($showAdminReports === true){

            return response()->json([

                'message' => 'success',
                'data' => [

                    'active_members' => $totalActiveMembers,
                    'pending_registrations' => $pendingRegistrations
        
                ],
            ]);
        }
        else{

            return response()->json([

                'message' => 'success',
                'data' => [

        
                ],
            ]);

        }
    }

    
}
