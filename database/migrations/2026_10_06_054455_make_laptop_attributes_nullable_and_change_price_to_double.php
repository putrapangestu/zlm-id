<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('brand')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->double('price')->nullable()->change();
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->nullable()->default('none')->change();
            $table->decimal('discount_value', 12, 2)->nullable()->default(0)->change();
            $table->boolean('is_discount_active')->nullable()->default(true)->change();
            $table->string('processor')->nullable()->change();
            $table->string('ram')->nullable()->change();
            $table->string('storage')->nullable()->change();
            $table->string('graphics')->nullable()->change();
            $table->string('display')->nullable()->change();
            $table->integer('stock')->nullable()->default(0)->change();
            $table->integer('uninspected_stock')->nullable()->default(0)->change();
            $table->integer('qc_passed_stock')->nullable()->default(0)->change();
            $table->boolean('is_featured')->nullable()->default(false)->change();
            $table->boolean('is_active')->nullable()->default(true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('brand')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
            $table->enum('discount_type', ['none', 'percentage', 'fixed'])->default('none')->nullable(false)->change();
            $table->decimal('discount_value', 12, 2)->default(0)->nullable(false)->change();
            $table->boolean('is_discount_active')->default(true)->nullable(false)->change();
            $table->string('processor')->nullable(false)->change();
            $table->string('ram')->nullable(false)->change();
            $table->string('storage')->nullable(false)->change();
            $table->string('graphics')->nullable(false)->change();
            $table->string('display')->nullable(false)->change();
            $table->integer('stock')->default(0)->nullable(false)->change();
            $table->integer('uninspected_stock')->default(0)->nullable(false)->change();
            $table->integer('qc_passed_stock')->default(0)->nullable(false)->change();
            $table->boolean('is_featured')->default(false)->nullable(false)->change();
            $table->boolean('is_active')->default(true)->nullable(false)->change();
        });
    }
};
