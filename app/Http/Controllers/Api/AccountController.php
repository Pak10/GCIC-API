<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Str;
use DB;

use App\Models\Administration\AccountType;
use App\Models\Administration\Account;

use App\Http\Resources\Administration\AccountTypeResource;
use App\Http\Resources\Administration\AccountResource;

class AccountController extends Controller
{
    
    public function getAccountTypes(Request $request)
    {
        $user = Auth::user();

        $accountTypes =  AccountType::where('visibilty', true)->get();

        return AccountTypeResource::collection($accountTypes);

    }

    public function getAccount(Request $request, $accountIdentifier)
    {
        $user = Auth::user();

    }

    public function getDiscretionaryAccounts(Request $request)
    {
        $user = Auth::user();

        if(!($user->can('view-accounts'))){

            return response()->json([

                'message' => 'User does not have access to this resource'
            ],403);
        }

        $accounts  = Account::withWhereHas(

            'type', function ($query) {
                $query->where('discretionary_account', true);
            },
        )
        ->get();

        return AccountResource::collection($accounts);

    }


}
