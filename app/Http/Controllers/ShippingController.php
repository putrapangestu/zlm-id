<?php

namespace App\Http\Controllers;

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
        return response()->json($this->rajaOngkir->getProvinces());
    }

    public function cities(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'province_id' => 'nullable|integer',
        ]);

        return response()->json($this->rajaOngkir->getCities($validated['province_id'] ?? null));
    }

    public function cost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);

        $couriers = config('rajaongkir.couriers', ['jne', 'pos', 'tiki']);
        $results = [];

        foreach ($couriers as $courier) {
            $costData = $this->rajaOngkir->getCost(
                $validated['destination'],
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
