<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_templates', function (Blueprint $table) {
            $table->id();
            $table->string('origin_city');
            $table->string('origin_city_ar')->nullable();
            $table->string('destination_city');
            $table->string('destination_city_ar')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->foreignId('vehicle_type_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique constraint for city pair + vehicle type
            $table->unique(['origin_city', 'destination_city', 'vehicle_type_id'], 'route_template_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_templates');
    }
};
