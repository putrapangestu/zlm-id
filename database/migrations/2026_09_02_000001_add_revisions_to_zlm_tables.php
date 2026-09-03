<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            if (!Schema::hasColumn('laptops', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
        });

        Schema::table('product_returns', function (Blueprint $table) {
            if (!Schema::hasColumn('product_returns', 'return_type')) {
                $table->enum('return_type', ['customer', 'supplier'])->default('customer')->after('return_number');
                $table->foreignUuid('restock_id')->nullable()->after('return_type')->constrained('restocks')->nullOnDelete();
                $table->foreignUuid('restock_item_id')->nullable()->after('restock_id')->constrained('restock_items')->nullOnDelete();
                $table->string('supplier_name')->nullable()->after('restock_item_id');
                $table->string('supplier_phone')->nullable()->after('supplier_name');
            }
        });

        Schema::table('product_items', function (Blueprint $table) {
            if (!Schema::hasColumn('product_items', 'is_sold')) {
                $table->boolean('is_sold')->default(false)->after('qc_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_items', function (Blueprint $table) {
            if (Schema::hasColumn('product_items', 'is_sold')) {
                $table->dropColumn('is_sold');
            }
        });

        Schema::table('product_returns', function (Blueprint $table) {
            if (Schema::hasColumn('product_returns', 'return_type')) {
                $table->dropForeign(['restock_id']);
                $table->dropForeign(['restock_item_id']);
                $table->dropColumn(['return_type', 'restock_id', 'restock_item_id', 'supplier_name', 'supplier_phone']);
            }
        });

        Schema::table('laptops', function (Blueprint $table) {
            if (Schema::hasColumn('laptops', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
