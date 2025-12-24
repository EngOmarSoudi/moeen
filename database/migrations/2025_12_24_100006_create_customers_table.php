<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('document_type', ['national_id', 'passport', 'residence_permit', 'driver_license', 'other'])->nullable();
            $table->string('document_no')->nullable();
            $table->string('issuing_authority')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('customer_statuses')->nullOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->text('special_case_note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['phone', 'document_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
