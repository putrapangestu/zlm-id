<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laptop_variants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('laptop_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->unique();
            $table->decimal('price_modifier', 10, 2)->default(0);
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('graphics')->nullable();
            $table->string('display')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('battery_life')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laptop_variants');
    }
};
