<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 50)->default('OPEN')->index();
            $table->string('document_verification_hash', 64)->unique()->comment('Immutable hash for document verification');
            $table->json('high_precision_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_cases');
    }
};
