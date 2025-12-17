<?php

namespace App\Models\InvestmentManagement;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\InvestmentManagement\InvestmentTransaction;

class AccountInvestment extends Pivot
{
    
    protected $fillable = [

        'id',
        'account_id',
        'investment_id',
        'amount_invested',
        'amount_returned',
        'interest_gained',
        'amount_withdrawn',
        'amount_deposited',
        'investment_changed',
        'direct_investment',
        'has_fixed_interest',
        'fixed_interest',
    ];

    public function transactions()
    {
        return $this->hasMany(InvestmentTransaction::class, 'account_investment_id', 'id');
    }

}
