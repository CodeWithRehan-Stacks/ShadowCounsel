<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_execution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_session_id')->constrained()->cascadeOnDelete();
            $table->string('step_name', 100)->index();
            $table->json('tool_calls')->nullable()->comment('Stores external API/math logs');
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->unsignedInteger('execution_time_ms')->default(0);
            $table->decimal('token_cost', 12, 6)->default(0)->comment('Micro-billing tracking');
            $table->timestamps();
            
            // Indexing for JSON tool calls extraction depending on queries,
            // but standard index on step_name + session_id handles most aggregations.
            $table->index(['agent_session_id', 'step_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_execution_logs');
    }
};
