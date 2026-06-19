<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number', 100)->nullable()->after('shipping_phone');
            $table->json('tracking_history')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('tracking_history');
            $table->date('estimated_delivery')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'tracking_history', 'shipped_at', 'estimated_delivery']);
        });
    }
};
