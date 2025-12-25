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
        // 1. Update travel_routes with coordinates
        Schema::table('travel_routes', function (Blueprint $table) {
            $table->decimal('origin_lat', 10, 8)->nullable()->after('origin');
            $table->decimal('origin_lng', 11, 8)->nullable()->after('origin_lat');
            $table->decimal('destination_lat', 10, 8)->nullable()->after('destination');
            $table->decimal('destination_lng', 11, 8)->nullable()->after('destination_lat');
        });

        // 2. Remove trip_type_id from trips
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['trip_type_id']);
            $table->dropColumn('trip_type_id');
        });

        // 3. Drop trip_types table
        Schema::dropIfExists('trip_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Recreate trip_types table
        Schema::create('trip_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Add trip_type_id back to trips
        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('trip_type_id')->nullable()->after('vehicle_type_id')->constrained()->nullOnDelete();
        });

        // 3. Remove coordinates from travel_routes
        Schema::table('travel_routes', function (Blueprint $table) {
            $table->dropColumn(['origin_lat', 'origin_lng', 'destination_lat', 'destination_lng']);
        });
    }
};
