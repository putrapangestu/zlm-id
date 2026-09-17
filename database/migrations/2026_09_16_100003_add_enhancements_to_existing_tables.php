<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Laptop SKU
        Schema::table('laptops', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('slug');
        });

        // 2. Restock Shipping status & tracking
        Schema::table('restocks', function (Blueprint $table) {
            $table->string('shipping_status')->default('received')->after('status');
            $table->string('shipping_courier')->nullable()->after('shipping_status');
            $table->string('tracking_number')->nullable()->after('shipping_courier');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            $table->timestamp('received_at')->nullable()->after('shipped_at');
        });

        // 3. ProductItem Costs (HPP Tracking)
        Schema::table('product_items', function (Blueprint $table) {
            $table->decimal('base_cost', 15, 2)->default(0)->after('serial_number');
            $table->decimal('additional_cost', 15, 2)->default(0)->after('base_cost');
            $table->decimal('final_cost', 15, 2)->default(0)->after('additional_cost');
        });

        // 4. Member Follow-up Status
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('needs_follow_up')->default(false)->after('member_points');
            $table->text('follow_up_notes')->nullable()->after('needs_follow_up');
            $table->timestamp('follow_up_date')->nullable()->after('follow_up_notes');
        });
    }

    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->dropColumn('sku');
        });

        Schema::table('restocks', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_status',
                'shipping_courier',
                'tracking_number',
                'shipped_at',
                'received_at',
            ]);
        });

        Schema::table('product_items', function (Blueprint $table) {
            $table->dropColumn([
                'base_cost',
                'additional_cost',
                'final_cost',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'needs_follow_up',
                'follow_up_notes',
                'follow_up_date',
            ]);
        });
    }
};
