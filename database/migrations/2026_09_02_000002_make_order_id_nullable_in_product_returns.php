<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_returns', function (Blueprint $table) {
            $table->foreignUuid('order_id')->nullable()->change();
            $table->foreignUuid('order_item_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_returns', function (Blueprint $table) {
            $table->foreignUuid('order_id')->nullable(false)->change();
            $table->foreignUuid('order_item_id')->nullable(false)->change();
        });
    }
};
