<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('restock_id')->nullable()->constrained('restocks')->nullOnDelete();
            $table->foreignUuid('laptop_id')->constrained('laptops')->cascadeOnDelete();
            $table->foreignUuid('laptop_variant_id')->nullable()->constrained('laptop_variants')->nullOnDelete();
            $table->string('sku')->nullable()->unique(); // SKU unik hanya setelah lolos QC
            $table->string('serial_number')->nullable();
            $table->enum('qc_status', ['pending', 'passed', 'failed', 'sold', 'returned'])->default('pending');
            $table->json('qc_checklist')->nullable(); // checklist kondisi fisik & fungsional
            $table->text('qc_notes')->nullable();
            $table->foreignUuid('qc_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('qc_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_items');
    }
};
