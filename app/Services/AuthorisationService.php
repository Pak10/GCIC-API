<?php

namespace App\Services;

use Illuminate\Support\Str;
use Log;
use Exception;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AuthorisationService {

    
    public function storeRole($roleVars)
    {


        try {

            DB::beginTransaction();

                $role = Role::create($roleVars);
            
            DB::commit();

            return $role;

        } catch (\Throwable $e) {

            Log::error('Error storing role:'. $e->getMessage());
            
            DB::rollBack();

            return false;
        }
    }
}