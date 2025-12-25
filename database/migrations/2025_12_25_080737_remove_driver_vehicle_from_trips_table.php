<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing trip records
        $trips = DB::table('trips')->get();
        
        // Drop the trips table
        Schema::dropIfExists('trips');
        
        // Recreate the trips table without driver_id and vehicle_id
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->nullOnDelete();
            $table->foreignId('trip_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('travel_route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            
            // Locations
            $table->string('origin');
            $table->string('destination');
            $table->decimal('origin_lat', 10, 8)->nullable();
            $table->decimal('origin_lng', 11, 8)->nullable();
            $table->decimal('destination_lat', 10, 8)->nullable();
            $table->decimal('destination_lng', 11, 8)->nullable();
            
            // Schedule
            $table->timestamp('start_at');
            $table->timestamp('completed_at')->nullable();
            
            // Details
            $table->enum('status', ['scheduled', 'pending', 'assigned', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->enum('service_kind', ['airport', 'hotel', 'city_tour'])->default('airport');
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
        
        // Insert the data back
        foreach ($trips as $trip) {
            DB::table('trips')->insert([
                'code' => $trip->code,
                'customer_id' => $trip->customer_id,
                'vehicle_type_id' => $trip->vehicle_type_id,
                'trip_type_id' => $trip->trip_type_id,
                'travel_route_id' => $trip->travel_route_id,
                'agent_id' => $trip->agent_id,
                'origin' => $trip->origin,
                'destination' => $trip->destination,
                'origin_lat' => $trip->origin_lat,
                'origin_lng' => $trip->origin_lng,
                'destination_lat' => $trip->destination_lat,
                'destination_lng' => $trip->destination_lng,
                'start_at' => $trip->start_at,
                'completed_at' => $trip->completed_at,
                'status' => $trip->status,
                'service_kind' => $trip->service_kind,
                'customer_segment' => $trip->customer_segment,
                'trip_leg' => $trip->trip_leg,
                'passenger_count' => $trip->passenger_count,
                'amount' => $trip->amount,
                'discount' => $trip->discount,
                'final_amount' => $trip->final_amount,
                'hotel_name' => $trip->hotel_name,
                'notes' => $trip->notes,
                'cancellation_reason' => $trip->cancellation_reason,
                'created_by' => $trip->created_by,
                'created_at' => $trip->created_at,
                'updated_at' => $trip->updated_at,
                'deleted_at' => $trip->deleted_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all existing trip records
        $trips = DB::table('trips')->get();
        
        // Drop the trips table
        Schema::dropIfExists('trips');
        
        // Recreate the trips table with the original columns
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->nullOnDelete();
            $table->foreignId('trip_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('travel_route_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained()->nullOnDelete();
            
            // Locations
            $table->string('origin');
            $table->string('destination');
            $table->decimal('origin_lat', 10, 8)->nullable();
            $table->decimal('origin_lng', 11, 8)->nullable();
            $table->decimal('destination_lat', 10, 8)->nullable();
            $table->decimal('destination_lng', 11, 8)->nullable();
            
            // Schedule
            $table->timestamp('start_at');
            $table->timestamp('completed_at')->nullable();
            
            // Details
            $table->enum('status', ['scheduled', 'pending', 'assigned', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->enum('service_kind', ['airport', 'hotel', 'city_tour'])->default('airport');
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
        
        // Insert the data back
        foreach ($trips as $trip) {
            DB::table('trips')->insert([
                'code' => $trip->code,
                'customer_id' => $trip->customer_id,
                'vehicle_type_id' => $trip->vehicle_type_id,
                'trip_type_id' => $trip->trip_type_id,
                'travel_route_id' => $trip->travel_route_id,
                'agent_id' => $trip->agent_id,
                'origin' => $trip->origin,
                'destination' => $trip->destination,
                'origin_lat' => $trip->origin_lat,
                'origin_lng' => $trip->origin_lng,
                'destination_lat' => $trip->destination_lat,
                'destination_lng' => $trip->destination_lng,
                'start_at' => $trip->start_at,
                'completed_at' => $trip->completed_at,
                'status' => $trip->status,
                'service_kind' => $trip->service_kind,
                'customer_segment' => $trip->customer_segment,
                'trip_leg' => $trip->trip_leg,
                'passenger_count' => $trip->passenger_count,
                'amount' => $trip->amount,
                'discount' => $trip->discount,
                'final_amount' => $trip->final_amount,
                'hotel_name' => $trip->hotel_name,
                'notes' => $trip->notes,
                'cancellation_reason' => $trip->cancellation_reason,
                'created_by' => $trip->created_by,
                'created_at' => $trip->created_at,
                'updated_at' => $trip->updated_at,
                'deleted_at' => $trip->deleted_at,
            ]);
        }
    }
};
