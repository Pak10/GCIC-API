<?php

namespace App\Http\Traits\Otp;

use App\Models\Transactions\Otp;
use Http;
use Log;
use App\Jobs\Emails\SendEmailOtp;
use Carbon\Carbon;

trait EmailTrait {



    public function emailVerifyOtp($user, $otp, $purpose, $mfa_source){


        $verify_otp = Otp::where('user_id', $user->id)
        ->where('mfa_source_id', $mfa_source->id)
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


    public function emailGenerateOtp($user, $otp){

        $send_mail = SendEmailOtp::dispatch($user, $otp);

        return true;
        
    }
}