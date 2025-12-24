<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('trip_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('travel_route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            
            // Locations
            $table->string('origin');
            $table->string('destination');
            
            // Schedule
            $table->timestamp('start_at');
            $table->timestamp('completed_at')->nullable();
            
            // Details
            $table->enum('status', ['scheduled', 'pending', 'assigned', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->enum('service_kind', ['trip', 'operation'])->default('trip');
            $table->enum('customer_segment', ['new', 'returning'])->default('new');
            $table->enum('trip_leg', ['outbound', 'return'])->default('outbound');
            $table->integer('passenger_count')->default(1);
            
            // Pricing
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            
            // Other
            $table->string('hotel_name')->nullable();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'start_at']);
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
