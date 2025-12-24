<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Support agent
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->boolean('is_internal')->default(false); // Internal note vs customer visible
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_messages');
    }
};
