<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->foreignId('vehicle_type_id')->constrained()->restrictOnDelete();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('vin')->nullable()->unique();
            $table->year('year')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->date('insurance_expiry')->nullable();
            $table->date('registration_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
