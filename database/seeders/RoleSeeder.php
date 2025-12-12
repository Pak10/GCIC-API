<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $super_admin =  Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);


        $member =  Role::create([

            'name' => 'Member',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::select('id', 'guard_name')->get()->keyBy('id');

        $super_admin->syncPermissions($permissions);

        $member->syncPermissions(['self-registration']);

    }
}
