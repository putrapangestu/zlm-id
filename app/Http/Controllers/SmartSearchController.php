<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;

class SmartSearchController extends Controller
{
    public function index()
    {
        $brands = Laptop::distinct()->pluck('brand');
        return view('landing.smart-search', compact('brands'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'budget' => 'required|numeric|min:1000000',
            'priority' => 'required|in:cpu,ram,storage,gpu,all',
            'usage' => 'nullable|in:office,programming,design,gaming,all',
            'brand' => 'nullable|string',
        ]);

        $budget = (int) $validated['budget'];
        $priority = $validated['priority'];
        $usage = $validated['usage'] ?? 'all';
        $brand = $validated['brand'] ?? null;

        // Bobot default
        $weights = ['budget' => 35, 'cpu' => 25, 'ram' => 15, 'storage' => 10, 'gpu' => 15];

        // Adjust bobot berdasarkan priority
        if ($priority !== 'all') {
            foreach ($weights as $key => $value) {
                if ($key === $priority) {
                    $weights[$key] += 5;
                } else {
                    $weights[$key] = max(5, $weights[$key] - 1);
                }
            }
        }

        // Query
        $query = Laptop::with('categories')->where('price', '<=', $budget);
        if ($brand) {
            $query->where('brand', $brand);
        }
        $laptops = $query->limit(100)->get();

        // Scoring
        $results = $laptops->map(function ($laptop) use ($budget, $weights, $usage) {
            $budgetScore = min(100, ($laptop->price / $budget) * 100);
            $cpuScore = $this->scoreProcessor($laptop->processor);
            $ramScore = $this->scoreRam($laptop->ram);
            $storageScore = $this->scoreStorage($laptop->storage);
            $gpuScore = $this->scoreGpu($laptop->graphics, $usage);

            $total = ($budgetScore * $weights['budget'] / 100)
                   + ($cpuScore * $weights['cpu'] / 100)
                   + ($ramScore * $weights['ram'] / 100)
                   + ($storageScore * $weights['storage'] / 100)
                   + ($gpuScore * $weights['gpu'] / 100);

            $laptop->match_score = round($total, 1);
            $laptop->scores = [
                'budget' => round($budgetScore, 1),
                'cpu' => round($cpuScore, 1),
                'ram' => round($ramScore, 1),
                'storage' => round($storageScore, 1),
                'gpu' => round($gpuScore, 1),
            ];

            return $laptop;
        });

        // Sort by match_score DESC
        $results = $results->sortByDesc('match_score')->values();

        $brands = Laptop::distinct()->pluck('brand');
        $totalLaptops = $results->count();

        return view('landing.smart-search', compact('results', 'brands', 'budget', 'priority', 'usage', 'brand', 'totalLaptops'));
    }

    private function scoreProcessor($processor)
    {
        $processor = strtolower($processor);
        if (preg_match('/(i9|ryzen\s?9|amd\s?9)/i', $processor)) return 100;
        if (preg_match('/(i7|ryzen\s?7|amd\s?7)/i', $processor)) return 80;
        if (preg_match('/(i5|ryzen\s?5|amd\s?5)/i', $processor)) return 60;
        if (preg_match('/(i3|ryzen\s?3|amd\s?3)/i', $processor)) return 40;
        if (preg_match('/(m[1-3]|m\s?pro|m\s?max)/i', $processor)) return 85;
        return 30;
    }

    private function scoreRam($ram)
    {
        $ram = strtolower($ram);
        if (preg_match('/^(32|64|48)\s*gb/i', $ram)) return 100;
        if (preg_match('/^(16|24)\s*gb/i', $ram)) return 80;
        if (preg_match('/^12\s*gb/i', $ram)) return 70;
        if (preg_match('/^8\s*gb/i', $ram)) return 60;
        if (preg_match('/^[46]\s*gb/i', $ram)) return 30;
        return 40;
    }

    private function scoreStorage($storage)
    {
        $storage = strtolower($storage);
        if (preg_match('/(1tb|1024|2tb|2048)/', $storage)) return 100;
        if (preg_match('/512/', $storage)) return 80;
        if (preg_match('/256/', $storage)) return 60;
        if (preg_match('/128/', $storage)) return 40;
        return 20;
    }

    private function scoreGpu($graphics, $usage)
    {
        $graphics = strtolower($graphics ?? '');

        // Deteksi dedicated GPU
        $hasDedicated = preg_match('/(rtx|gtx|rx\s?|radeon|arc|nvidia|amd\s?)/i', $graphics);
        $isHighEnd = preg_match('/(rtx\s?40[789]|rtx\s?50[789]|rtx\s?30[789]|rx\s?7[89]|rx\s?9)/i', $graphics);
        $isMidEnd = preg_match('/(rtx\s?30[0-6]|rtx\s?40[0-6]|gtx\s?16[0-9]|gtx\s?10[0-9]|rx\s?6[0-9])/i', $graphics);

        if ($usage === 'gaming' || $usage === 'design') {
            if ($isHighEnd) return 100;
            if ($isMidEnd) return 80;
            if ($hasDedicated) return 60;
            return 30;
        }

        if ($hasDedicated) return 80;
        return 40;
    }
}
