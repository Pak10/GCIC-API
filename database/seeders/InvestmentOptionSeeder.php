<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentManagement\InvestmentOption;

class InvestmentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $investmentOption = InvestmentOption::create([

            'investment_option' => 'Bonds',
        ]);
    }
}
