<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentManagement\InvestmentPlan;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $investmentPlan  = InvestmentPlan::create([

            'investment_plan' => 'Original Plan',
            'has_fixed_interest' => true,
            'fixed_interest' => 10
        ]);

        
    }
}
