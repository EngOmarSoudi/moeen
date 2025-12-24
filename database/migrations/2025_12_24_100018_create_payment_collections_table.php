<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete(); // Driver who collected
            $table->decimal('amount', 10, 2);    // Amount due
            $table->decimal('received', 10, 2)->default(0); // Amount received
            $table->decimal('change', 10, 2)->default(0);   // Change given
            $table->enum('method', ['cash', 'card', 'link', 'return', 'bank_transfer'])->default('cash');
            $table->enum('status', ['pending', 'confirmed', 'partial', 'canceled'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            
            $table->index(['trip_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_collections');
    }
};
