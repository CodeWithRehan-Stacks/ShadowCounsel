<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_case_id')->constrained()->cascadeOnDelete();
            $table->string('orchestrator_version', 50)->default('Nemotron-3-Ultra');
            $table->unsignedInteger('context_window_usage')->default(0);
            $table->unsignedInteger('total_tokens')->default(0);
            $table->json('session_metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_sessions');
    }
};
