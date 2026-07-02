<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentExecutionLog extends Model
{
    protected $fillable = [
        'agent_session_id',
        'step_name',
        'tool_calls',
        'input_tokens',
        'output_tokens',
        'execution_time_ms',
        'token_cost',
    ];

    protected $casts = [
        'tool_calls' => 'array',
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
        'execution_time_ms' => 'integer',
        'token_cost' => 'decimal:6',
    ];

    public function agentSession(): BelongsTo
    {
        return $this->belongsTo(AgentSession::class);
    }
}
