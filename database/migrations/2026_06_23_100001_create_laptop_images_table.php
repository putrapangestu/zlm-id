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
        Schema::create('laptop_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('laptop_id');
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->text('caption')->nullable();
            $table->timestamps();

            $table->foreign('laptop_id')
                ->references('id')
                ->on('laptops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptop_images');
    }
};
