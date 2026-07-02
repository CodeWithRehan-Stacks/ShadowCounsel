<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->json('corporate_entity_metadata')->nullable();
            $table->string('jurisdiction', 100)->index();
            $table->date('fiscal_year_start');
            $table->date('fiscal_year_end');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_portfolios');
    }
};
