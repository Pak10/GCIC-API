<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
use Str;
use Carbon\Carbon;
use App\Models\UserStatus;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $status =  UserStatus::select('id', 'status')->where('status', 'Active')->first();


        $user =  User::create(
            [
                'email' => 'admin@gcic.com',
                'phone_number' => '256708288453',
                'password' =>  bcrypt('GCIC@1234!'),
                'name' => 'Admin Account',
                'category' => 'administrator',
                'user_status_id' => $status->id,
            ],

        );

        /* Super Admin Account will approve his account */

        $user->update([

            'approved_by' => $user->id,
            'reviewed_by' => $user->id,

        ]);

        $user_details =  UserDetail::create(
            [
                'user_id' => $user->id,
                'first_name' => 'Admin',
                'last_name' =>  'Account',
                'physical_address' => 'Bukoto',     
            ]
        );

        $user->assignRole('Super Admin');

    }
}
