<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setting extends Model implements  Auditable
{
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;

    protected $fillable  = [

        'gcic_investment_deduction',
    ];

    
}
