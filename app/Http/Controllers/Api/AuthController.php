<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Administration\MfaSource;
use App\Models\Transactions\Otp;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Administration\UserResource;
use App\Http\Traits\Otp\OtpTrait;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Str;
use Mail;
use DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    use OtpTrait;
    
    public function login(LoginRequest $request)
    {
        // Validate request
        $credentials = $request->validated();

        $user =  User::where('email', $credentials['email'])->first();

        if($user === null){

            return response()->json([
                'message' => 'Invalid Credentials',
            ], 400);
        }

        if (Hash::check($credentials['password'], $user->password)) {

            $mfa_source =  MfaSource::where('mfa_source', 'email')->first();

            if($mfa_source === null){

                return response()->json([
                    'message' => 'Invalid MFA Option',
                ], 400);

            }

            $generate_auth_code = $this->generateOtp($user, $mfa_source, 'login');

            return response()->json([

                'message' => 'Authentication code has been shared',
                'transaction_reference' => $generate_auth_code['transaction_reference']
            ]);
        
        }

        return response()->json([
            'message' => 'Invalid Credentials',
        ], 400);

    }

    public function logout(Request $request)
    {

        Auth::guard('web')->logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();

        return response()->json([

            'message' => 'Logged out successfully'
        ]);
    }


    public function verifyAuthOtp(Request $request)
    {

        $validated = $request->validate([

            'otp' => 'required',
            'transaction_reference' => 'required|uuid',

        ]);

        $purpose = 'login';

        $otp =  Otp::where('transaction_reference', $validated['transaction_reference'])
        ->where('otp', $validated['otp'])
        ->where('purpose', $purpose)
        ->first();

        if($otp  === null){

            return response()->json([

                'message' => 'Invalid OTP provided'
            ],400);
        }

        $user =  User::where('id', $otp->user_id)->first();

        if($user  === null){

            return response()->json([

                'message' => 'Invalid User provided. Contact support'

            ],400);
        }

        $mfa_source =  MfaSource::where('mfa_source', 'email')->first();

        if($mfa_source  === null){

            return response()->json([

                'message' => 'Invalid MFA option. Contact support'

            ],400);
        }


        $verify_otp =  $this->verifyOtp($mfa_source, $validated['otp'], $user, $purpose);

        if($verify_otp){

            Auth::login($user);
            
            $request->session()->regenerate();

            $user = User::with(['details', 'roles', 'userStatus'])->where('id', $user->id)->first();

            return new UserResource($user);
        }

        else{

            return response()->json([

                'message' => 'Invalid OTP provided'

            ],400);
        }
    }


    public function getProfile(Request $request)
    {
        $user =  Auth::user();

        $user = User::with(['details', 'roles', 'userStatus', 'accounts'])->where('id', $user->id)->first();

        return new UserResource($user);

    }
}
