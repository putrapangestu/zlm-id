<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_laptop', function (Blueprint $table) {
            $table->foreignUuid('category_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('laptop_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'laptop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_laptop');
    }
};
