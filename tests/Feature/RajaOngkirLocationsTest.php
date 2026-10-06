<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\User;
use Database\Seeders\RajaOngkirLocationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RajaOngkirLocationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_seeder_preserves_local_ids_and_imports_rajaongkir_ids(): void
    {
        $this->seed(RajaOngkirLocationSeeder::class);

        $this->assertDatabaseHas('provinces', [
            'id' => 1,
            'id_rajaongkir' => 1,
            'name' => 'NUSA TENGGARA BARAT (NTB)',
        ]);
        $this->assertDatabaseHas('cities', [
            'id' => 1,
            'id_rajaongkir' => 17,
            'name' => 'BURU SELATAN',
        ]);

        $city = City::with('province')->findOrFail(1);
        $this->assertSame('MALUKU', $city->province->name);
        $this->assertSame(34, DB::table('provinces')->count());
        $this->assertSame(574, DB::table('cities')->count());
        $this->assertSame(6767, DB::table('subdistricts')->count());
    }

    public function test_shipping_cost_uses_external_city_id_from_local_destination_id(): void
    {
        $this->seed(RajaOngkirLocationSeeder::class);
        config([
            'rajaongkir.api_key' => 'test-api-key',
            'rajaongkir.base_url' => 'https://api.rajaongkir.test/starter',
            'rajaongkir.couriers' => ['jne'],
        ]);

        Http::fake([
            'api.rajaongkir.test/*' => Http::response([
                'rajaongkir' => [
                    'results' => [[
                        'code' => 'jne',
                        'name' => 'Jalur Nugraha Ekakurir',
                        'costs' => [],
                    ]],
                ],
            ]),
        ]);

        $user = User::factory()->create();
        $this->actingAs($user)
            ->getJson('/shipping/provinces')
            ->assertOk()
            ->assertJsonFragment(['id' => 1, 'id_rajaongkir' => 1, 'name' => 'NUSA TENGGARA BARAT (NTB)']);

        $this->getJson('/shipping/cities?province_id=2')
            ->assertOk()
            ->assertJsonFragment(['id' => 1, 'province_id' => 2, 'id_rajaongkir' => 17, 'name' => 'BURU SELATAN']);

        $this->postJson('/shipping/cost', [
            'destination' => 1,
            'weight' => 1000,
        ])->assertOk();

        Http::assertSent(fn (ClientRequest $request) => $request->url() === 'https://api.rajaongkir.test/starter/cost'
            && $request['destination'] === 17
        );
    }
}
