<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Polymorphic wallets for customers, drivers, and agents
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->morphs('walletable'); // walletable_type, walletable_id
            $table->decimal('balance', 15, 2)->default(0);           // Current available balance
            $table->decimal('total_debt', 15, 2)->default(0);        // Customer: total owed to company
            $table->decimal('total_collected', 15, 2)->default(0);   // Driver: cash collected from customers
            $table->decimal('budget_limit', 15, 2)->nullable();      // Agent: max booking budget
            $table->decimal('budget_used', 15, 2)->default(0);       // Agent: amount consumed for bookings
            $table->timestamps();
            
            $table->unique(['walletable_type', 'walletable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
