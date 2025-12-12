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

            ['name' => 'view-investment-options', 'guard_name' => 'web'],
            ['name' => 'create-investment-option', 'guard_name' => 'web'],

            ['name' => 'view-investment-plans', 'guard_name' => 'web'],
            ['name' => 'create-investment-plan', 'guard_name' => 'web'],
            ['name' => 'update-investment-plan', 'guard_name' => 'web'],
            ['name' => 'delete-investment-plan', 'guard_name' => 'web'],

            ['name' => 'record-deposit', 'guard_name' => 'web'],
            ['name' => 'approve-deposit', 'guard_name' => 'web'],
            ['name' => 'reject-deposit', 'guard_name' => 'web'],

            ['name' => 'record-withdrawal', 'guard_name' => 'web'],
            ['name' => 'approve-withdrawal', 'guard_name' => 'web'],
            ['name' => 'reject-withdrawal', 'guard_name' => 'web'],

            ['name' => 'view-transaction-types', 'guard_name' => 'web'],
            ['name' => 'view-transactions', 'guard_name' => 'web'],
            ['name' => 'approve-transaction', 'guard_name' => 'web'],
            ['name' => 'reject-transaction', 'guard_name' => 'web'],

            ['name' => 'create-investment', 'guard_name' => 'web'],
            ['name' => 'view-investments', 'guard_name' => 'web'],
            ['name' => 'cancel-investment', 'guard_name' => 'web'],
            ['name' => 'close-investment', 'guard_name' => 'web'],
    

            ['name' => 'create-user', 'guard_name' => 'web'],
            ['name' => 'view-users', 'guard_name' => 'web'],
            ['name' => 'update-user', 'guard_name' => 'web'],           
            ['name' => 'delete-user', 'guard_name' => 'web'],

            ['name' => 'create-role', 'guard_name' => 'web'],
            ['name' => 'view-roles', 'guard_name' => 'web'],
            ['name' => 'update-role', 'guard_name' => 'web'],           
            ['name' => 'delete-role', 'guard_name' => 'web'],
            ['name' => 'view-permissions', 'guard_name' => 'web'],
            ['name' => 'attach-permissions', 'guard_name' => 'web'],

            ['name' => 'view-accounts', 'guard_name' => 'web'],
            ['name' => 'update-account', 'guard_name' => 'web'],



        ];    

        $permissions =  Permission::insert($seeded_permissions);
    }
}
