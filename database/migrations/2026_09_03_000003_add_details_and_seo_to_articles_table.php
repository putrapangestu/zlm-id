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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('thumbnail')->nullable()->after('slug');
            $table->string('category')->default('Panduan')->after('thumbnail');
            $table->text('excerpt')->nullable()->after('category');
            $table->boolean('is_published')->default(true)->after('description');
            $table->unsignedInteger('views_count')->default(0)->after('is_published');
            $table->string('meta_title')->nullable()->after('views_count');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'thumbnail',
                'category',
                'excerpt',
                'is_published',
                'views_count',
                'meta_title',
                'meta_description',
                'meta_keywords',
            ]);
        });
    }
};
