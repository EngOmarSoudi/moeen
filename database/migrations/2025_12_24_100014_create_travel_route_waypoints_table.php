<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_route_waypoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('travel_route_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('order');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('stop_type', ['pickup', 'dropoff', 'waypoint'])->default('waypoint');
            $table->integer('wait_time_minutes')->default(0);
            $table->timestamps();
            
            $table->index(['travel_route_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_route_waypoints');
    }
};
