<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_returns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('return_number')->unique();
            $table->foreignUuid('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignUuid('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('product_item_id')->nullable()->constrained('product_items')->nullOnDelete();
            $table->enum('reason', ['defective_item', 'wrong_item', 'not_as_described', 'change_of_mind', 'other']);
            $table->text('customer_notes')->nullable();
            $table->json('proof_images')->nullable(); // array url foto bukti
            $table->enum('status', ['pending', 'approved', 'rejected', 'item_received', 'completed', 'cancelled'])->default('pending');
            $table->enum('resolution_type', ['refund', 'replacement', 'repair'])->default('refund');
            $table->decimal('refund_amount', 15, 2)->default(0);
            $table->enum('stock_action', ['return_to_quarantine_qc', 'return_to_stock', 'scrap_defective', 'no_stock_change'])->default('return_to_quarantine_qc');
            $table->foreignUuid('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_returns');
    }
};
