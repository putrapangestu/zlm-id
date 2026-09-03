<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('device_code')->unique(); // e.g. POS-01
            $table->string('device_name'); // e.g. Kasir Utama Toko
            $table->string('device_uuid')->unique(); // UUID client browser
            $table->timestamp('last_sync_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('pos_device_id')->nullable()->constrained('pos_devices')->nullOnDelete();
            $table->string('action'); // e.g. POS_SALE, QC_PASS, RESTOCK_CREATE, RETURN_APPROVE
            $table->string('model_type')->nullable();
            $table->string('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('pos_devices');
    }
};
