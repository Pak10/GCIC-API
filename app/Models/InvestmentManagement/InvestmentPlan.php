<?php

namespace App\Models\InvestmentManagement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\InvestmentManagement\InvestmentOption;

class InvestmentPlan extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable  = [

        'investment_plan',
        'has_fixed_interest',
        'fixed_interest',
        'share_option',
        'mandatory_tithe',

    ];

    protected $casts = [

        'has_fixed_interest' => 'boolean',
        'share_option' => 'boolean', 
        'mandatory_tithe' => 'boolean',  
    ];


    public function options(): BelongsToMany
    {
        return $this->belongsToMany(InvestmentOption::class, 'investment_option_plan', 'investment_plan_id', 'investment_option_id')->withPivot('allocation');
    }
}
