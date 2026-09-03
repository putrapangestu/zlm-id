<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('laptop_id')->constrained('laptops')->cascadeOnDelete();
            $table->foreignUuid('laptop_variant_id')->nullable()->constrained('laptop_variants')->nullOnDelete();
            $table->foreignUuid('product_item_id')->nullable()->constrained('product_items')->nullOnDelete();
            $table->string('type'); // PURCHASE, QC_PASSED, QC_FAILED, SALE_ONLINE, SALE_POS, RETURN_IN, RETURN_OUT, ADJUSTMENT
            $table->integer('quantity');
            $table->integer('stock_before')->default(0);
            $table->integer('stock_after')->default(0);
            $table->string('reference_type')->nullable();
            $table->uuid('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
