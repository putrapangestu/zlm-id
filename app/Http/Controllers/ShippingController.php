<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Province;
use App\Services\RajaOngkirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        protected RajaOngkirService $rajaOngkir
    ) {}

    public function provinces(): JsonResponse
    {
        return response()->json(
            Province::query()
                ->whereNotNull('id_rajaongkir')
                ->orderBy('name')
                ->get(['id', 'id_rajaongkir', 'name'])
        );
    }

    public function cities(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'province_id' => 'nullable|integer|exists:provinces,id',
        ]);

        $cities = City::query()
            ->whereNotNull('id_rajaongkir')
            ->when($validated['province_id'] ?? null, fn ($query, $provinceId) => $query->where('province_id', $provinceId))
            ->orderBy('name')
            ->get(['id', 'province_id', 'id_rajaongkir', 'name']);

        return response()->json($cities);
    }

    public function cost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination' => 'required|integer|exists:cities,id',
            'weight' => 'required|integer|min:1',
        ]);
        $city = City::query()
            ->whereKey($validated['destination'])
            ->whereNotNull('id_rajaongkir')
            ->firstOrFail();

        $couriers = config('rajaongkir.couriers', ['jne', 'pos', 'tiki']);
        $results = [];

        foreach ($couriers as $courier) {
            $costData = $this->rajaOngkir->getCost(
                (int) $city->id_rajaongkir,
                $validated['weight'],
                $courier
            );

            if (! empty($costData)) {
                $results = array_merge($results, $costData);
            }
        }

        return response()->json($results);
    }
}
