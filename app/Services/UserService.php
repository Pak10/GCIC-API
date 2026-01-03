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
use App\Models\Administration\UserRegistration;
use App\Jobs\Emails\SendUserAccountEmail;


class UserService {


    public function storeUser($userVars,$user,$selfRegistration)
    {

        try{

            DB::beginTransaction();

                $userRegistration = UserRegistration::create([

                    'data' => $userVars,
                    'self_registration' => $selfRegistration,
                    'registration_reference' => $userVars['registration_reference'],
                    'category' => $userVars['category'],
                ]); 

                $userStatus =  UserStatus::select('id', 'status')->where('status', 'Active')->first();

                $password =  Str::password(8);

                $userVars['password'] = bcrypt($password);
                $userVars['name'] = $userVars['first_name'].' '.$userVars['last_name'].' '.$userVars['other_name'];
                $userVars['user_status_id'] = $userStatus->id;

                $user = User::create($userVars);

                $user->details()->create($userVars); 

                $user->assignRole($userVars['role']);

                // Send email

                $sendMail = SendUserAccountEmail::dispatch($user, $password);


            DB::commit();

            return $user;

        }
        catch (\Throwable $e) {
                
            Log::error('Error storing member:'. $e->getMessage());

            DB::rollBack();

            return false;
        }


    }

}