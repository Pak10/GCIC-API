<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seeded_permissions = [

            ['name' => 'create-member', 'guard_name' => 'web'],
            ['name' => 'view-members', 'guard_name' => 'web'],
            ['name' => 'update-memeber', 'guard_name' => 'web'],           
            ['name' => 'delete-member', 'guard_name' => 'web'],

            ['name' => 'view-member-registrations', 'guard_name' => 'web'],
            ['name' => 'review-member-registrations', 'guard_name' => 'web'],
            ['name' => 'approve-member-registrations', 'guard_name' => 'web'],
            ['name' => 'reject-member-registrations', 'guard_name' => 'web'],


        ];    

        $permissions =  Permission::insert($seeded_permissions);
    }
}
