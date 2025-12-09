<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\InvestmentManagement\Investment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class AccountType extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable = [

        'account_type',
        'account_prefix',
        'visibility',
        'description',
        'directly_invests',
    ];

    public function investments(): BelongsToMany
    {
        return $this->belongsToMany(Investment::class, 'account_type_investment', 'account_type_id', 'investment_id')->withPivot('amount_invested');
    }


}
