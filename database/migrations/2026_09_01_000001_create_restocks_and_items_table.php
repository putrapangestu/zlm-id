<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('restock_number')->unique();
            $table->string('supplier_name');
            $table->string('supplier_phone')->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('purchase_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['received', 'partially_checked', 'completed'])->default('received');
            $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('restock_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('restock_id')->constrained('restocks')->cascadeOnDelete();
            $table->foreignUuid('laptop_id')->constrained('laptops')->cascadeOnDelete();
            $table->foreignUuid('laptop_variant_id')->nullable()->constrained('laptop_variants')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('purchase_price', 15, 2)->default(0); // HPP per unit
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restock_items');
        Schema::dropIfExists('restocks');
    }
};
