<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administration\AccountType;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $original =  AccountType::create([
            
            'account_type' => 'Original',
            'directly_invests' => true,
            'account_prefix' => 'GC_O'
        ]);

        $ordinary =  AccountType::create([
            
            'account_type' => 'Ordinary',
            'directly_invests' => false,
            'account_prefix' => 'GC_R'
        ]);

        $serviceAccount =  AccountType::create([
            
            'account_type' => 'Service',
            'directly_invests' => true,
            'account_prefix' => 'GC_S',
            'visibilty' => false,
        ]);
    }
}
