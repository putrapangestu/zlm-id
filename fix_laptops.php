<?php

namespace App\Console;

use App\Models\Laptop;
use Illuminate\Support\Str;

$laptops = Laptop::all();
echo "Total: " . $laptops->count() . "\n";

// Fix slugs on originals
$originals = $laptops->filter(function ($l) {
    return empty($l->slug);
});

echo "Originals (empty slug): " . $originals->count() . "\n";

$originals->each(function ($l) {
    $l->slug = Str::slug($l->name);
    $l->save();
    echo "  Updated: {$l->name} -> {$l->slug}\n";
});

// Delete duplicates
$originalSlugs = $originals->pluck('slug')->toArray();
$originalIds = $originals->pluck('id')->toArray();

$dupes = Laptop::whereIn('slug', $originalSlugs)
    ->whereNotIn('id', $originalIds)
    ->get();

echo "\nDuplicates to delete: " . $dupes->count() . "\n";

$dupes->each(function ($l) {
    echo "  Deleting: {$l->name} ({$l->slug})\n";
    $l->variants()->delete();
    $l->categories()->detach();
    $l->delete();
});

echo "\nFinal count: " . Laptop::count() . "\n";
echo "Done.\n";
