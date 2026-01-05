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


        ///////////////////////////////////////ACTIVE MEMBERS //////////////////////////////////////////////////////
        if($showAdminReports === true){

        
            $activeUserStatus = $status =  UserStatus::select('id', 'status')->where('status', 'Active')->first();
            $totalActiveMembers =  User::where('category', 'member')->where('user_status_id', $activeUserStatus->id)
            ->count();
        }

        ///////////////////////////////////////PENDING REGISTRATIONS //////////////////////////////////////////////////////

        if($showAdminReports === true){

            $pendingRegistrations =  UserRegistration::where('status', 'pending')->count();

        }


        ///////////////////////////////////////TOTAL DEPOSITS //////////////////////////////////////////////////////

        $transactionType = TransactionType::where('transaction_type', 'Deposit')->first();

        if($showAdminReports === true){

            $totalDeposits =  AccountTransaction::where('transaction_type_id', $transactionType->id)
            ->whereNotNull('approved_by')
            ->sum('amount');

        }
        else{

            $totalDeposits =  AccountTransaction::where('transaction_type_id', $transactionType->id)
            ->whereIn('account_id', $accounts)
            ->whereNotNull('approved_by')
            ->sum('amount');
        }



        ///////////////////////////////////////TOTAL WITHDRAWALS //////////////////////////////////////////////////////

        $transactionType = TransactionType::where('transaction_type', 'Withdrawal')->first();

        if($showAdminReports === true){

            $totalWithdrawals =  AccountTransaction::where('transaction_type_id', $transactionType->id)
            ->whereNotNull('approved_by')
            ->sum('amount');

        }
        else{

            $totalWithdrawals =  AccountTransaction::where('transaction_type_id', $transactionType->id)
            ->whereIn('account_id', $accounts)
            ->whereNotNull('approved_by')
            ->sum('amount');
        }

        ////////////////////////////////////REFERRALS //////////////////////////////////////////////////////


        if($showAdminReports === false){

            $totalReferrals =  User::where('referred_by'. $user->id)->count();
        }


        if($showAdminReports === true){

            return response()->json([

                'message' => 'success',
                'data' => [

                    'active_members' => $totalActiveMembers,
                    'pending_registrations' => $pendingRegistrations,
                    'total_deposits' => $totalDeposits,
                    'total_withdrawals' => $totalWithdrawals
        
                ],
            ]);
        }
        else{



            return response()->json([

                'message' => 'success',
                'data' => [
                    
                    'total_withdrawals' => $totalWithdrawals,
                    'total_referrals' => $totalReferrals
                ], 
            ]);

        }
    }

    
}
