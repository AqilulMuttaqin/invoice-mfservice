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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique();
            $table->string('invoice_code')->unique()->nullable();
            $table->foreignId('device_type_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->string('device_name');
            $table->string('imei_serial_number')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('complaint');
            $table->text('physical_condition')->nullable();
            $table->text('technician_notes')->nullable();
            $table->date('received_date');
            $table->decimal('estimated_finish_day', 30, 0)->nullable();
            $table->date('completed_date')->nullable();
            $table->date('picked_up_date')->nullable();
            $table->enum('status', [
                'received',
                'repairing',
                'completed',
                'picked_up',
                'cancelled'
            ])->default('received');
            $table->decimal('remaining_warranty_claim', 10, 0)->default(0);
            $table->decimal('warranty_days', 10, 0)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
