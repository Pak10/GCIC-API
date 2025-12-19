<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserRegistration extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable = [

        'self_registration',
        'category',
        'registration_reference',
        'data',
        'data->approved_by',
        'data->reviewed_by',
        'data->investment_plan_id',
        'data->account_type_id',
        'status',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
