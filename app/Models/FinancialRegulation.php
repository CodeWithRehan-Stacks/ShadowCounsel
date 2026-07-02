<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialRegulation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source_agency',
        'regulation_code',
        'title',
        'description',
        'metadata',
        'effective_date',
    ];

    protected $casts = [
        'metadata' => 'array',
        'effective_date' => 'date',
    ];
}
