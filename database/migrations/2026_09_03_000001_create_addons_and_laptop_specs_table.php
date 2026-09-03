<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('addons')) {
            Schema::create('addons', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->decimal('price', 12, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('image_url')->nullable();
                $table->boolean('is_recommended')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('laptops', function (Blueprint $table) {
            if (!Schema::hasColumn('laptops', 'ports')) {
                $table->text('ports')->nullable()->after('display');
                $table->string('camera')->nullable()->after('ports');
                $table->string('audio')->nullable()->after('camera');
                $table->string('connectivity')->nullable()->after('audio');
                $table->string('color')->nullable()->after('connectivity');
                $table->string('warranty')->nullable()->after('color');
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (!Schema::hasColumn('cart_items', 'addon_id')) {
                $table->foreignUuid('addon_id')->nullable()->after('laptop_variant_id')->constrained('addons')->nullOnDelete();
                $table->decimal('addon_price', 12, 2)->default(0)->after('unit_price');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'addon_id')) {
                $table->foreignUuid('addon_id')->nullable()->after('product_item_id')->constrained('addons')->nullOnDelete();
                $table->string('addon_name')->nullable()->after('addon_id');
                $table->decimal('addon_price', 12, 2)->default(0)->after('discount_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'addon_id')) {
                $table->dropForeign(['addon_id']);
                $table->dropColumn(['addon_id', 'addon_name', 'addon_price']);
            }
        });

        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'addon_id')) {
                $table->dropForeign(['addon_id']);
                $table->dropColumn(['addon_id', 'addon_price']);
            }
        });

        Schema::table('laptops', function (Blueprint $table) {
            if (Schema::hasColumn('laptops', 'ports')) {
                $table->dropColumn(['ports', 'camera', 'audio', 'connectivity', 'color', 'warranty']);
            }
        });

        Schema::dropIfExists('addons');
    }
};
