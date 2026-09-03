<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('laptops', function (Blueprint $table) {
            $table->foreignUuid('brand_id')->nullable()->after('brand')->constrained('brands')->nullOnDelete();
        });

        // Migrate existing distinct string brands into brands table
        $existingBrands = DB::table('laptops')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand');

        $defaultBrands = collect(['Lenovo', 'Dell', 'HP', 'ASUS', 'Acer', 'Apple', 'MSI', 'ThinkPad', 'Microsoft', 'Toshiba'])
            ->merge($existingBrands)
            ->unique()
            ->values();

        foreach ($defaultBrands as $idx => $brandName) {
            $brandId = (string) Str::uuid();
            $slug = Str::slug($brandName);
            
            // Check if already inserted
            $existing = DB::table('brands')->where('name', $brandName)->first();
            if (!$existing) {
                DB::table('brands')->insert([
                    'id' => $brandId,
                    'name' => $brandName,
                    'slug' => $slug ?: 'brand-' . $idx,
                    'description' => "Produsen & brand resmi perangkat laptop {$brandName}.",
                    'is_active' => true,
                    'sort_order' => $idx + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $brandId = $existing->id;
            }

            // Bind laptops to this brand_id
            DB::table('laptops')
                ->where('brand', $brandName)
                ->whereNull('brand_id')
                ->update(['brand_id' => $brandId]);
        }
    }

    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });

        Schema::dropIfExists('brands');
    }
};
