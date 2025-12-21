<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentManagement\InvestmentPlan;
use App\Models\InvestmentManagement\InvestmentOption;

class InvestmentPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $investmentPlan  = InvestmentPlan::create([

            'investment_plan' => 'Default Plan',
            'has_fixed_interest' => true,
            'fixed_interest' => 10,
            'share_profit' => false,
            'mandatory_tithe' => true,
        ]);


        $investmentOption = InvestmentOption::orderBy('created_at', 'asc')->first();

        $investmentPlan->options()->attach($investmentOption->id, ['allocation'=>100]);

        
    }
}
