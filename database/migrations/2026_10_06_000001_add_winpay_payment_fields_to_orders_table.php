<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('winpay_reference')->nullable()->index();
            $table->string('winpay_contract_id')->nullable();
            $table->text('winpay_qr_url')->nullable();
            $table->text('winpay_qr_content')->nullable();
            $table->string('winpay_virtual_account_no')->nullable();
            $table->string('winpay_channel', 30)->nullable();
            $table->timestamp('winpay_expiry')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['winpay_reference']);
            $table->dropColumn([
                'winpay_reference',
                'winpay_contract_id',
                'winpay_qr_url',
                'winpay_qr_content',
                'winpay_virtual_account_no',
                'winpay_channel',
                'winpay_expiry',
            ]);
        });
    }
};
