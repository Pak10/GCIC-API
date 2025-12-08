<?php

namespace App\Models\Transactions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AccountTransaction extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable = [

        'account_id',
        'amount',
        'transaction_type_id',
        'transaction_reference',
        'method_of_payment',
        'date_of_transaction',
        'created_by',
        'data',
        'approved_by',
        'rejected_by',
        'status',

    ];

    protected $casts = [
        'data' => 'array',
    ];
}
