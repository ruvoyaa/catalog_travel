<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('provider', 100);
            $table->string('model', 100);
            $table->unsignedInteger('dimensions');
            $table->text('source_text');
            $table->json('embedding');
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_embeddings');
    }
};
