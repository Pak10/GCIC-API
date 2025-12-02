<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MfaSource extends Model implements  Auditable
{
    
    use HasFactory, AuditableTrait, SoftDeletes,HasUuids;
    
    protected $fillable = [

        'mfa_source'
    ];

}
