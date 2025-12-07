<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administration\MfaSource;
use Str;

class MfaSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $mfa_source = MfaSource::create([

            'display_name' => 'Phone Number',
            'mfa_source' => 'phone',
            'system_generated_otp' => true,
        
        ]);

        $mfa_source = MfaSource::create([

            'display_name' => 'Email',
            'mfa_source' => 'email',
            'system_generated_otp' => true,
    
        ]);
    }
}
