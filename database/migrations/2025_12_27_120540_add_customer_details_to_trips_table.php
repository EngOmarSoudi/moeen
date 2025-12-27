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
        Schema::table('trips', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->comment('Customer name snapshot');
            $table->string('customer_phone')->nullable()->comment('Customer phone snapshot');
            $table->string('customer_email')->nullable()->comment('Customer email snapshot');
            $table->string('customer_nationality')->nullable()->comment('Customer nationality snapshot');
            $table->string('customer_document_type')->nullable()->comment('Document type snapshot');
            $table->string('customer_document_no')->nullable()->comment('Document number snapshot');
            $table->string('customer_issuing_authority')->nullable()->comment('Issuing authority snapshot');
            $table->string('customer_status')->nullable()->comment('Customer status snapshot');
            $table->string('customer_agent_name')->nullable()->comment('Assigned agent name snapshot');
            $table->text('customer_notes')->nullable()->comment('Customer general notes snapshot');
            $table->text('customer_special_case_note')->nullable()->comment('Customer special case notes snapshot');
            $table->string('customer_emergency_contact_name')->nullable()->comment('Emergency contact name snapshot');
            $table->string('customer_emergency_contact_phone')->nullable()->comment('Emergency contact phone snapshot');
            $table->string('customer_emergency_contact_email')->nullable()->comment('Emergency contact email snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_phone',
                'customer_email',
                'customer_nationality',
                'customer_document_type',
                'customer_document_no',
                'customer_issuing_authority',
                'customer_status',
                'customer_agent_name',
                'customer_notes',
                'customer_special_case_note',
                'customer_emergency_contact_name',
                'customer_emergency_contact_phone',
                'customer_emergency_contact_email',
            ]);
        });
    }
};
