<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluation_form_id')->constrained()->restrictOnDelete();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete(); // User who filled it
            $table->morphs('target'); // Who is being evaluated (Driver, Vehicle, etc.)
            $table->decimal('score', 3, 2); // Calculated average score
            $table->json('answers'); // Stored as JSON key-value pairs of field_id => value
            $table->text('comments')->nullable();
            $table->timestamps();
            
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_evaluations');
    }
};
