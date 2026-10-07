<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->string('ram_2')->nullable();
            $table->string('storage_2')->nullable();
        });

        Schema::table('product_items', function (Blueprint $table) {
            $table->json('received_specs')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('expected_subtotal', 12, 2)->default(0)->after('subtotal');
        });
        DB::table('orders')->update(['expected_subtotal' => DB::raw('subtotal')]);

        Schema::table('restock_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('restock_items', function (Blueprint $table) {
            $table->decimal('purchase_price', 15, 2)->default(0)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('expected_subtotal');
        });

        Schema::table('product_items', function (Blueprint $table) {
            $table->dropColumn('received_specs');
        });

        Schema::table('laptops', function (Blueprint $table) {
            $table->dropColumn(['ram_2', 'storage_2']);
        });
    }
};
