<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_portfolio_id',
        'title',
        'description',
        'status',
        'document_verification_hash',
        'high_precision_data',
    ];

    protected $casts = [
        'high_precision_data' => 'array',
    ];

    public function clientPortfolio(): BelongsTo
    {
        return $this->belongsTo(ClientPortfolio::class);
    }

    public function agentSessions(): HasMany
    {
        return $this->hasMany(AgentSession::class);
    }
}
