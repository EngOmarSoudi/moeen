<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_tracking_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 6, 2)->nullable(); // km/h
            $table->decimal('heading', 5, 2)->nullable(); // degrees 0-360
            $table->decimal('accuracy', 8, 2)->nullable(); // meters
            $table->timestamp('recorded_at');
            $table->timestamps();
            
            // Index for efficient querying
            $table->index(['trip_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_tracking_points');
    }
};
