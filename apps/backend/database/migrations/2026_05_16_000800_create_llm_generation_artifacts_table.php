<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llm_generation_artifacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 100);
            $table->string('model', 100);
            $table->string('status', 30)->default('generated');
            $table->json('source_payload')->nullable();
            $table->json('generated_payload');
            $table->timestamp('generated_at');
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_generation_artifacts');
    }
};
