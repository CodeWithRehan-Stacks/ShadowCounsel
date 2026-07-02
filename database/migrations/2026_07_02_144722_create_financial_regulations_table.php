<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_regulations', function (Blueprint $table) {
            $table->id();
            $table->string('source_agency', 50)->index();
            $table->string('regulation_code', 100);
            $table->text('title');
            $table->longText('description')->nullable();
            $table->json('metadata')->nullable()->comment('Stores document embeddings and references');
            $table->date('effective_date')->index();
            $table->timestamps();
            $table->softDeletes();

            // Composite index for rapid lookup
            $table->unique(['source_agency', 'regulation_code']);
            // Full text index on title and description for semantic/keyword search
            $table->fullText(['title', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_regulations');
    }
};
