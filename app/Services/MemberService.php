<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserStatus;
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

    public function updateRegistrationStatus($memberRegistration, $memberRegistrationVars, $user){

        try{

            $status = $memberRegistrationVars['status'];

            DB::beginTransaction();

                $memberRegistration->update([

                    'status' => $status
                ]);

                if($status === 'approved'){

                    $memberRegistration->update([

                        'data->approved_by' => $user->id,
                        'data->investment_plan_id' => $memberRegistrationVars['investment_plan_id'],
                        'data->account_type_id' => $memberRegistrationVars['account_type_id'],
                    ]);

                    $userAccount = $this->setupMemberAccount($memberRegistration);

                    if(!$userAccount){

                        return false;
                    }
                }
                else if($status === 'reviewed'){

                    $memberRegistration->update([
                        
                        'data->reviewed_by' => $user->id,
                    ]);

                }
                else if($status === 'rejected'){

                    $memberRegistration->update([
                        
                        'data->rejected_by' => $user->id,
                    ]);

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


    public function setupMemberAccount($memberRegistration)
    {

        try{

            DB::beginTransaction();
            
                $memberRegistrationData = $memberRegistration->data;

                $userStatus =  UserStatus::select('id', 'status')->where('status', 'Active')->first();

                $password =  Str::password(8);

                if($memberRegistration->self_registration == false){

                    $memberRegistrationData['password'] = bcrypt($password);
                    $memberRegistrationData['name'] = $memberRegistrationData['first_name'].' '.$memberRegistrationData['last_name'];
                    $memberRegistrationData['user_status_id'] = $userStatus->id;
    
                    $user = User::create($memberRegistrationData);
                }
                else{

                    $user =  User::where('id', $memberRegistrationData['user_id'])->first();

                    if($user === null){

                        Log::error('Error setting up accont: Cannot find user account');

                        DB::rollBack();
            
                        return false;
                    }

                    $user->update([

                        'user_status_id' => $userStatus->id,
                        'phone_number' => $memberRegistrationData['phone_number'],
                        'approved_by' => $memberRegistrationData['approved_by'],
                        'reviewed_by' => $memberRegistrationData['reviewed_by'],
                        'referred_by' => $memberRegistrationData['referred_by'],
                    ]);

                }

                $user->details()->create($memberRegistrationData); 

                $account = Account::create([

                    'user_id' => $user->id,
                    'investment_plan_id' => $memberRegistrationData['investment_plan_id'],
                    'account_identifier' => Str::uuid(),
                    'account_type_id' => $memberRegistrationData['account_type_id'],
                ]);

            DB::commit();

            return $account;

        }
        catch (\Throwable $e) {
                
            Log::error('Error setting up user account:'. $e->getMessage());

            DB::rollBack();

            return false;
        }


    }

}

