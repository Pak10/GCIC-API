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
}
