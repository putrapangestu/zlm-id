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
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('processor');
            $table->string('ram');
            $table->string('storage');
            $table->string('graphics');
            $table->string('display');
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('battery_life')->nullable();
            $table->string('image_url')->nullable();
            $table->enum('category', ['gaming', 'business', 'student', 'ultrabook']);
            $table->integer('stock')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};
