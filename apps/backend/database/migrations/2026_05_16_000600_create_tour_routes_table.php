<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->decimal('center_lat', 10, 6);
            $table->decimal('center_lng', 10, 6);
            $table->unsignedTinyInteger('zoom')->default(8);
            $table->json('route_points')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_routes');
    }
};
