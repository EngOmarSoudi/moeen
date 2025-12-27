<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
            
            // Assignment tracking
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'declined', 'canceled'])->default('pending');
            $table->integer('sequence_number')->default(1); // For multiple driver assignments to same trip
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->unique(['trip_id', 'driver_id', 'sequence_number']);
            $table->index(['driver_id', 'status']);
            $table->index(['trip_id', 'status']);
            $table->index(['status', 'assigned_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_assignments');
    }
};
