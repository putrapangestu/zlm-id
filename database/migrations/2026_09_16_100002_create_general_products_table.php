<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->change();
            $table->string('slug')->nullable()->after('name');
            $table->foreignUuid('category_id')->nullable()->after('slug')->constrained('categories')->nullOnDelete();
            $table->string('sku')->nullable()->unique()->after('category_id');
            $table->integer('stock')->default(0)->after('sku');
            $table->decimal('cost_price', 15, 2)->default(0)->after('price');
            $table->boolean('is_active')->default(true)->after('cost_price');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'slug',
                'category_id',
                'sku',
                'stock',
                'cost_price',
                'is_active',
                'deleted_at',
            ]);
        });
    }
};
