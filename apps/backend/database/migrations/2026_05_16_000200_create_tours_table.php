<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('full_description')->nullable();
            $table->unsignedInteger('duration_days');
            $table->string('duration_label')->nullable();
            $table->string('status', 20)->default('draft');
            $table->foreignId('primary_category_id')->nullable()->constrained('tour_categories')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
