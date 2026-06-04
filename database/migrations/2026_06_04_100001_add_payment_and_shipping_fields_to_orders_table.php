<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Payment fields (after payment_status)
            $table->string('xendit_invoice_id')->nullable()->after('payment_status');
            $table->text('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
            $table->timestamp('xendit_expiry')->nullable()->after('xendit_invoice_url');
            $table->string('proof_of_transfer', 255)->nullable()->after('xendit_expiry');
            $table->timestamp('paid_at')->nullable()->after('proof_of_transfer');
            $table->foreignUuid('approved_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();

            // Shipping fields (after approved_by)
            $table->decimal('shipping_cost', 15, 2)->nullable()->after('approved_by');
            $table->string('shipping_courier', 50)->nullable()->after('shipping_cost');
            $table->string('shipping_service', 100)->nullable()->after('shipping_courier');
            $table->string('shipping_etd', 50)->nullable()->after('shipping_service');
            $table->string('shipping_city_id', 20)->nullable()->after('shipping_etd');
            $table->string('shipping_city_name', 255)->nullable()->after('shipping_city_id');
            $table->string('shipping_province_name', 255)->nullable()->after('shipping_city_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'xendit_invoice_id',
                'xendit_invoice_url',
                'xendit_expiry',
                'proof_of_transfer',
                'paid_at',
                'approved_by',
                'shipping_cost',
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
                'shipping_city_id',
                'shipping_city_name',
                'shipping_province_name',
            ]);
        });
    }
};
