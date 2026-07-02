<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPortfolio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'corporate_entity_metadata',
        'jurisdiction',
        'fiscal_year_start',
        'fiscal_year_end',
    ];

    protected $casts = [
        'corporate_entity_metadata' => 'array',
        'fiscal_year_start' => 'date',
        'fiscal_year_end' => 'date',
    ];

    public function financialCases(): HasMany
    {
        return $this->hasMany(FinancialCase::class);
    }
}
