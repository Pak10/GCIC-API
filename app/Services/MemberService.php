<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Administration\Account;
use App\Models\Administration\UserRegistration;

class MemberService {


    /*
        Register User function. 

        This will create the save the registration request

        However user profile and account will not be created at this point
    */
    
    public function registerMember($userVars, $loggedInUser, $selfRegistration)
    {
        /*
            The userVars variable holds the user registration data
            We will save this in the data column. This will then be used to populate the user profile and account when the user is apporved
        */

        try{

            DB::beginTransaction();

                $memberRegistration = UserRegistration::create([

                    'data' => $userVars,
                    'self_registration' => $selfRegistration,
                    'registration_reference' => $userVars['registration_reference'],
                    'category' => $userVars['category'],
                ]); 

            DB::commit();

            return $memberRegistration;
        }
        catch (\Throwable $e) {
                
            Log::error('Error storing member:'. $e->getMessage());

            DB::rollBack();

            return false;
        }
    }

    public function updateRegistrationStatus($memberRegistration, $status){

        try{

            DB::beginTransaction();

                $memberRegistration->update([

                    'status' => $status
                ]);

                if($status === 'approved'){

                    $userAccount = $this->setupUserAccount();

                    if(!$userAccount){

                        return false;
                    }
                }
            
            DB::commit();

            return $memberRegistration;

        }

        catch (\Throwable $e) {
                
            Log::error('Error storing member:'. $e->getMessage());

            DB::rollBack();

            return false;
        }
    }


    public function setupUserAccount($memberRegistration)
    {

        $memberRegistrationData = $memberRegistration->data;

        $password =  Str::password(8);

        $memberRegistrationData['password'] = bcrypt($password);

        $user = User::create($memberRegistrationData);

        $user->details()->save($memberRegistrationData); 

    }

}

