<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'financial_case_id',
        'orchestrator_version',
        'context_window_usage',
        'total_tokens',
        'session_metadata',
    ];

    protected $casts = [
        'session_metadata' => 'array',
        'context_window_usage' => 'integer',
        'total_tokens' => 'integer',
    ];

    public function financialCase(): BelongsTo
    {
        return $this->belongsTo(FinancialCase::class);
    }

    public function agentExecutionLogs(): HasMany
    {
        return $this->hasMany(AgentExecutionLog::class);
    }
}
