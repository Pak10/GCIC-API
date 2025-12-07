<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserStatus;
use Str;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $userStatus =  UserStatus::insert([
            [
                'id' => Str::uuid(),
                'status' => 'Active',

            ],
            [
                'id' => Str::uuid(),
                'status' => 'Suspended',
            ],
            [
                'id' => Str::uuid(),
                'status' => 'Deactivated',
            ],
            [
                'id' => Str::uuid(),
                'status' => 'Under Review',
            ]

        ]);
    }
}
