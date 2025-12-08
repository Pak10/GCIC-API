<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            
            TransactionTypeSeeder::class,
            AccountTypeSeeder::class,
            MfaSourceSeeder::class,
            InvestmentOptionSeeder::class,
            InvestmentPlanSeeder::class,
            UserStatusSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            DefaultUserSeeder::class,

        ]);
    }
}
