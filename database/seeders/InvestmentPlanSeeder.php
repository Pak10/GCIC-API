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

        $investmentOption = InvestmentOption::orderBy('created_at', 'asc')->first();

        $defaultOriginalPlan  = InvestmentPlan::create([

            'investment_plan' => 'Default Original Plan',
            'has_fixed_interest' => false,
            'fixed_interest' => null,
            'share_profit' => true,
            'mandatory_tithe' => true,
        ]);

        $defaultOriginalPlan->options()->attach($investmentOption->id, ['allocation'=>100]);


        $defaultOrdinaryPlan  = InvestmentPlan::create([

            'investment_plan' => 'Default Ordinary Plan',
            'has_fixed_interest' => false,
            'fixed_interest' => null,
            'share_profit' => false,
            'mandatory_tithe' => false,
        ]);

        $defaultOrdinaryPlan->options()->attach($investmentOption->id, ['allocation'=>100]);


        $fixedOrdinaryPlan  = InvestmentPlan::create([

            'investment_plan' => 'Ordinary Fixed 2% Interest Plan',
            'has_fixed_interest' => true,
            'fixed_interest' => 2,
            'share_profit' => false,
            'mandatory_tithe' => false,
        ]);

        $fixedOrdinaryPlan->options()->attach($investmentOption->id, ['allocation'=>100]);


        
    }
}
