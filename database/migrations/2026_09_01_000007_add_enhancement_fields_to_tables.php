<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->string('member_number')->nullable()->unique()->after('phone_number');
            $table->string('member_tier')->default('bronze')->after('member_number');
            $table->integer('member_points')->default(0)->after('member_tier');
            $table->timestamp('joined_member_at')->nullable()->after('member_points');
        });

        Schema::table('laptops', function (Blueprint $table) {
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none')->after('price');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
            $table->timestamp('discount_start_at')->nullable()->after('discount_value');
            $table->timestamp('discount_end_at')->nullable()->after('discount_start_at');
            $table->boolean('is_discount_active')->default(true)->after('discount_end_at');
            $table->integer('uninspected_stock')->default(0)->after('stock');
            $table->integer('qc_passed_stock')->default(0)->after('uninspected_stock');
        });

        Schema::table('laptop_variants', function (Blueprint $table) {
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none')->after('price_modifier');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
            $table->timestamp('discount_start_at')->nullable()->after('discount_value');
            $table->timestamp('discount_end_at')->nullable()->after('discount_start_at');
            $table->boolean('is_discount_active')->default(true)->after('discount_end_at');
            $table->integer('uninspected_stock')->default(0)->after('stock');
            $table->integer('qc_passed_stock')->default(0)->after('uninspected_stock');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('source', ['online', 'pos'])->default('online')->after('order_number');
            $table->foreignUuid('pos_device_id')->nullable()->after('source')->constrained('pos_devices')->nullOnDelete();
            $table->foreignUuid('cashier_id')->nullable()->after('pos_device_id')->constrained('users')->nullOnDelete();
            $table->string('client_order_uuid')->nullable()->unique()->after('cashier_id');
            $table->timestamp('client_created_at')->nullable()->after('client_order_uuid');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('total');
            $table->decimal('member_discount_amount', 12, 2)->default(0)->after('discount_amount');
            $table->integer('points_earned')->default(0)->after('member_discount_amount');
            $table->integer('points_used')->default(0)->after('points_earned');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignUuid('product_item_id')->nullable()->after('laptop_variant_id')->constrained('product_items')->nullOnDelete();
            $table->string('sku')->nullable()->after('product_item_id');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_item_id']);
            $table->dropColumn(['product_item_id', 'sku', 'discount_amount']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['pos_device_id']);
            $table->dropForeign(['cashier_id']);
            $table->dropColumn([
                'source', 'pos_device_id', 'cashier_id', 'client_order_uuid',
                'client_created_at', 'discount_amount', 'member_discount_amount',
                'points_earned', 'points_used'
            ]);
        });

        Schema::table('laptop_variants', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type', 'discount_value', 'discount_start_at',
                'discount_end_at', 'is_discount_active', 'uninspected_stock', 'qc_passed_stock'
            ]);
        });

        Schema::table('laptops', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type', 'discount_value', 'discount_start_at',
                'discount_end_at', 'is_discount_active', 'uninspected_stock', 'qc_passed_stock'
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number', 'member_number', 'member_tier', 'member_points', 'joined_member_at'
            ]);
        });
    }
};
