<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Account extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fiillable = [

        'account_type_id',
        'account_number',
        'user_id',
        'balance',
        'total_deposit',
        'interest_gained',
        'referral_commission_earned',
    ];
}
