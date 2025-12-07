<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Administration\UserRegistration;

class UserService {


    /*
        Register User function. 

        This will create the save the registration request

        However user profile and account will not be created at this point
    */
    
    public function registerUser($userVars, $loggedInUser, $selfRegistration)
    {
        /*
            The userVars variable holds the user registration data
            We will save this in the data column. This will then be used to populate the user profile and account when the user is apporved
        */

        try{

            DB::beginTransaction();

                $userRegistration = UserRegistration::create([

                    'data' => $userVars,
                    'self_registration' => $selfRegistration,
                    'category' => $userVars['category'],
                ]); 

            DB::commit();

            return $userRegistration;
        }
        catch (\Throwable $e) {
                
            Log::error('Error storing customer:'. $e->getMessage());

            DB::rollBack();

            return false;
        }




    }

}

