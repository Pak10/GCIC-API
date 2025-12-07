<?php

namespace App\Http\Traits\Otp;

use App\Models\Transactions\Otp;
use App\Http\Traits\Otp\EmailTrait;
use App\Http\Traits\Otp\SmsTrait;
use Str;

trait OtpTrait {

    use EmailTrait, SmsTrait;

    public function verifyOtp($mfa_source, $otp, $user, $purpose){

        if($mfa_source->mfa_source == 'saaspass'){

            $verify_otp = $this->saasPassVerifyOtp($user, $otp);

            return $verify_otp;
        }
        else if($mfa_source->mfa_source == 'email'){

            $verify_otp = $this->emailVerifyOtp($user, $otp, $purpose,$mfa_source);

            return $verify_otp;
        }
        else if($mfa_source->mfa_source == 'phone'){

            $verify_otp = $this->smsVerifyOtp($user, $otp, $purpose);

            return $verify_otp;
        }
        else{

            return false;
        }

    }


    public function generateOtp($user, $mfa_source, $purpose){

        $otp =  $this->generateOtpCode($user, $purpose, $mfa_source);

        $otp_code =  $otp['otp_code'];
        
        if($mfa_source->mfa_source == 'saaspass'){

            return $otp;
        }
        else if($mfa_source->mfa_source  == 'email'){

            $generate_otp = $this->emailGenerateOtp($user, $otp_code);

            return $otp;
        }
        else if($mfa_source->mfa_source  == 'phone'){

            $generate_otp = $this->smsGenerateOtp($user, $otp_code);

            return $otp;
        }
    }


    public function generateOtpCode($user, $purpose, $mfa_source){


        if($mfa_source->system_generated_otp == true){

            $otp_code = mt_rand(100000, 999999);
        }
        else{

            $otp_code = null;
        }

        $transaction_reference =  Str::uuid();

        $otp =  Otp::create([

            'user_id' => $user->id,
            'mfa_source_id' => $mfa_source->id,
            'otp' => $otp_code,
            'is_active' => true,
            'purpose' => $purpose,
            'transaction_reference' => $transaction_reference,
        ]);

        return $otp = [

            'otp_code' => $otp_code,
            'transaction_reference' => $transaction_reference
        ];
    }


}