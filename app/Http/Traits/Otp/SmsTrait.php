<?php

namespace App\Http\Traits\Otp;

use App\Models\Transactions\Otp;
use Http;
use Log;
use Carbon\Carbon;

trait SmsTrait {


    public function smsGenerateOTP($user, $otp){

        $generate_otp = Http::withHeaders([

            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'apiKey' => config('app.at_key'),
        ])
        ->post(config('app.at_url').'/version1/messaging/bulk', [
            'username' => config('app.at_username'),
            'message' => 'Your Kweli ID otp code is '.$otp,
            'from' => 'IFORTIFY',
            'phoneNumbers' => [
                $user->phone_number
            ]
        ]);

    }



    public function smsVerifyOtp($user, $otp, $purpose){


        $verify_otp = Otp::where('user_id', $user->id)
        ->where('mfa_source_id', $user->mfa_source_id)
        ->where('is_active', 1)
        ->where('otp', $otp)
        ->where('purpose', $purpose)
        ->orderBy('created_at', 'desc')
        ->first();

        if($verify_otp === null){

            return false;
        }

        $current_timestamp = Carbon::now();
        $current_timestamp =  $current_timestamp->toDateTimeString();

        if($verify_otp->created_at->diffInMinutes($current_timestamp) > 3){

            return false;
        }

        $verify_otp->update([

            'is_active' => 0
        ]);

        return true;

    }

}