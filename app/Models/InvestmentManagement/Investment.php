<?php

namespace App\Models\InvestmentManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\InvestmentManagement\AccountInvestment;
use App\Models\Administration\Account;

class Investment extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable = [

        'investment_option_id',
        'amount',
        'transaction_reference',
        'date_of_investment',
        'date_of_recovery',
        'expected_date_of_recovery',
        'cancelled_by',
        'closed_by',
        'settled_by',
        'profit',
        'share_profit',
        'amount_returned',
        'created_by',
        'status',
    ];

    public function accountLedger(): BelongsToMany
    {
        return $this->belongsToMany(Account::class)
        ->using(AccountInvestment::class)
        ->withPivot(['amount_invested']);
    }


}
