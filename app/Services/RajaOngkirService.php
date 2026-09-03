<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected int $originCityId;

    public function __construct()
    {
        $this->apiKey = (string) config('rajaongkir.api_key', '');
        $this->baseUrl = (string) config('rajaongkir.base_url', 'https://api.rajaongkir.com/starter');
        $this->originCityId = (int) config('rajaongkir.origin_city_id', 256);
    }

    protected function headers(): array
    {
        return ['key' => $this->apiKey];
    }

    public function getProvinces(): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $response = Http::withHeaders($this->headers())->get($this->baseUrl . '/province');

        if ($response->failed()) {
            Log::error('RajaOngkir getProvinces failed', ['response' => $response->body()]);
            return [];
        }

        return $response->json('rajaongkir.results') ?? [];
    }

    public function getCities(?int $provinceId = null): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $params = [];
        if ($provinceId) {
            $params['province'] = $provinceId;
        }

        $response = Http::withHeaders($this->headers())->get($this->baseUrl . '/city', $params);

        if ($response->failed()) {
            Log::error('RajaOngkir getCities failed', ['response' => $response->body()]);
            return [];
        }

        return $response->json('rajaongkir.results') ?? [];
    }

    public function getCost(int $destination, int $weight, string $courier): array
    {
        if (empty($this->apiKey)) {
            return [];
        }

        $response = Http::withHeaders($this->headers())->asForm()->post($this->baseUrl . '/cost', [
            'origin' => $this->originCityId,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ]);

        if ($response->failed()) {
            Log::error('RajaOngkir getCost failed', [
                'destination' => $destination,
                'courier' => $courier,
                'response' => $response->body(),
            ]);
            return [];
        }

        return $response->json('rajaongkir.results') ?? [];
    }
}
