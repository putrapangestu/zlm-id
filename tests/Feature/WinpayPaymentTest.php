<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\User;
use App\Services\WinpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WinpayPaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private string $publicKeyPath;

    private string $privateKeyPath;

    private string $opensslConfigPath;

    private \OpenSSLAsymmetricKey $privateKey;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'buyer']);
        $this->user = User::factory()->create();
        $this->user->assignRole('buyer');

        $this->opensslConfigPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'winpay-openssl-'.Str::uuid().'.cnf';
        file_put_contents($this->opensslConfigPath, "[req]\ndistinguished_name = req_distinguished_name\n[req_distinguished_name]\n");

        $key = openssl_pkey_new([
            'config' => $this->opensslConfigPath,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 1024,
        ]);
        $this->assertNotFalse($key);
        $this->privateKey = $key;
        $details = openssl_pkey_get_details($key);
        $this->assertIsArray($details);

        $this->publicKeyPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'winpay-test-'.Str::uuid().'.pem';
        file_put_contents($this->publicKeyPath, $details['key']);
        $this->privateKeyPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'winpay-test-private-'.Str::uuid().'.pem';
        openssl_pkey_export($key, $privateKeyContents, null, ['config' => $this->opensslConfigPath]);
        file_put_contents($this->privateKeyPath, $privateKeyContents);

        config([
            'winpay.partner_id' => 'test-partner',
            'winpay.public_key_path' => $this->publicKeyPath,
            'winpay.private_key_path' => $this->privateKeyPath,
        ]);

        Mail::fake();
    }

    protected function tearDown(): void
    {
        if (isset($this->publicKeyPath) && is_file($this->publicKeyPath)) {
            unlink($this->publicKeyPath);
        }
        if (isset($this->privateKeyPath) && is_file($this->privateKeyPath)) {
            unlink($this->privateKeyPath);
        }
        if (isset($this->opensslConfigPath) && is_file($this->opensslConfigPath)) {
            unlink($this->opensslConfigPath);
        }

        parent::tearDown();
    }

    public function test_user_can_create_a_winpay_qris_payment(): void
    {
        $this->createCart();

        $this->mock(WinpayService::class, function ($mock) {
            $mock->shouldReceive('createQrisPayment')
                ->once()
                ->andReturn([
                    'reference' => 'ORD-20261006-ABCDE',
                    'contract_id' => 'qris-contract',
                    'qr_url' => 'https://sandbox-payment.winpay.id/qr/test',
                    'qr_content' => null,
                    'virtual_account_no' => null,
                    'channel' => 'QRIS',
                    'expiry' => now()->addHour()->toIso8601String(),
                ]);
        });

        $response = $this->actingAs($this->user)->post(route('orders.place'), $this->checkoutData([
            'payment_method' => 'winpay_qris',
        ]));

        $order = $this->user->orders()->firstOrFail();
        $response->assertRedirect(route('orders.confirmation', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_method' => 'winpay_qris',
            'winpay_reference' => 'ORD-20261006-ABCDE',
            'winpay_contract_id' => 'qris-contract',
            'winpay_channel' => 'QRIS',
        ]);

        $this->actingAs($this->user)
            ->get(route('orders.confirmation', $order))
            ->assertOk()
            ->assertSee('Pindai QRIS')
            ->assertSee('https://sandbox-payment.winpay.id/qr/test');
    }

    public function test_checkout_uses_the_configured_winpay_gateway(): void
    {
        config(['payment.gateway' => 'winpay']);
        $this->createCart();

        $this->actingAs($this->user)
            ->get(route('landing.checkout'))
            ->assertOk()
            ->assertSee('Winpay — QRIS')
            ->assertSee('Winpay — Virtual Account')
            ->assertDontSee('<option value="xendit">', false);
    }

    public function test_checkout_uses_xendit_when_configured(): void
    {
        config(['payment.gateway' => 'xendit']);
        $this->createCart();

        $this->actingAs($this->user)
            ->get(route('landing.checkout'))
            ->assertOk()
            ->assertSee('Xendit')
            ->assertDontSee('Winpay — QRIS');
    }

    public function test_checkout_rejects_a_gateway_different_from_the_configured_gateway(): void
    {
        config(['payment.gateway' => 'winpay']);
        $this->createCart();

        $this->actingAs($this->user)
            ->post(route('orders.place'), $this->checkoutData([
                'payment_method' => 'xendit',
            ]))
            ->assertSessionHasErrors('payment_method');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_user_can_create_a_winpay_virtual_account_payment(): void
    {
        $this->createCart();

        $this->mock(WinpayService::class, function ($mock) {
            $mock->shouldReceive('createVirtualAccount')
                ->once()
                ->with(\Mockery::type(Order::class), 'BCA')
                ->andReturn([
                    'reference' => 'ORD-20261006-ABCDE',
                    'contract_id' => 'va-contract',
                    'qr_url' => null,
                    'qr_content' => null,
                    'virtual_account_no' => '12345678901234',
                    'channel' => 'BCA',
                    'expiry' => now()->addHour()->toIso8601String(),
                ]);
        });

        $response = $this->actingAs($this->user)->post(route('orders.place'), $this->checkoutData([
            'payment_method' => 'winpay_va',
            'payment_channel' => 'BCA',
        ]));

        $order = $this->user->orders()->firstOrFail();
        $response->assertRedirect(route('orders.confirmation', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_method' => 'winpay_va',
            'winpay_virtual_account_no' => '12345678901234',
            'winpay_channel' => 'BCA',
        ]);

        $this->actingAs($this->user)
            ->get(route('orders.confirmation', $order))
            ->assertOk()
            ->assertSee('12345678901234')
            ->assertSee('BCA');
    }

    public function test_virtual_account_checkout_requires_a_supported_bank(): void
    {
        $this->createCart();

        $this->actingAs($this->user)
            ->post(route('orders.place'), $this->checkoutData([
                'payment_method' => 'winpay_va',
                'payment_channel' => 'UNKNOWN',
            ]))
            ->assertSessionHasErrors('payment_channel');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_winpay_api_requests_are_signed_for_qris_and_virtual_account(): void
    {
        Http::fake([
            'https://sandbox-snap.winpay.id/v1.0/qr/qr-mpm-generate' => Http::response([
                'responseCode' => '2004700',
                'responseMessage' => 'Success',
                'partnerReferenceNo' => 'order-reference',
                'qrUrl' => 'https://sandbox-payment.winpay.id/qr/test',
                'additionalInfo' => ['contractId' => 'qris-contract'],
            ]),
            'https://sandbox-snap.winpay.id/v1.0/transfer-va/create-va' => Http::response([
                'responseCode' => '2002700',
                'responseMessage' => 'Success',
                'virtualAccountData' => [
                    'trxId' => 'order-reference',
                    'virtualAccountNo' => ' 12345678901234 ',
                    'expiredDate' => now()->addHour()->toIso8601String(),
                    'additionalInfo' => ['contractId' => 'va-contract'],
                ],
            ]),
        ]);

        $order = $this->createOrder('winpay_qris');
        $order->order_number = 'order-reference';
        $qris = app(WinpayService::class)->createQrisPayment($order);
        $va = app(WinpayService::class)->createVirtualAccount($order, 'BCA');

        $this->assertSame('https://sandbox-payment.winpay.id/qr/test', $qris['qr_url']);
        $this->assertSame('12345678901234', $va['virtual_account_no']);
        $this->assertSignedRequest('/v1.0/qr/qr-mpm-generate');
        $this->assertSignedRequest('/v1.0/transfer-va/create-va');
    }

    public function test_winpay_qris_callback_marks_matching_order_paid(): void
    {
        $order = $this->createOrder('winpay_qris');
        $payload = [
            'originalPartnerReferenceNo' => $order->winpay_reference,
            'latestTransactionStatus' => '00',
            'amount' => [
                'value' => '100.00',
                'currency' => 'IDR',
            ],
        ];

        $response = $this->postJson(
            route('webhooks.winpay.qris'),
            $payload,
            $this->signatureHeaders('/webhooks/winpay/v1.0/qr/qr-mpm-notify', $payload),
        );

        $response->assertOk()
            ->assertJson(['responseCode' => '2005200']);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
    }

    public function test_winpay_virtual_account_callback_marks_matching_order_paid(): void
    {
        $order = $this->createOrder('winpay_va');
        $payload = [
            'trxId' => $order->winpay_reference,
            'paidAmount' => [
                'value' => '100.00',
                'currency' => 'IDR',
            ],
        ];

        $response = $this->postJson(
            route('webhooks.winpay.virtual-account'),
            $payload,
            $this->signatureHeaders('/webhooks/winpay/v1.0/transfer-va/payment', $payload),
        );

        $response->assertOk()
            ->assertJson(['responseCode' => '2002500']);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'status' => 'processing',
        ]);
    }

    public function test_winpay_callback_rejects_an_invalid_signature(): void
    {
        $order = $this->createOrder('winpay_qris');

        $response = $this->postJson(route('webhooks.winpay.qris'), [
            'originalPartnerReferenceNo' => $order->winpay_reference,
            'latestTransactionStatus' => '00',
            'amount' => ['value' => '100.00', 'currency' => 'IDR'],
        ], [
            'X-Partner-ID' => 'test-partner',
            'X-Timestamp' => now()->toIso8601String(),
            'X-Signature' => 'invalid',
        ]);

        $response->assertUnauthorized();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_winpay_callback_does_not_pay_an_order_with_a_mismatched_amount(): void
    {
        $order = $this->createOrder('winpay_qris');
        $payload = [
            'originalPartnerReferenceNo' => $order->winpay_reference,
            'latestTransactionStatus' => '00',
            'amount' => ['value' => '99.00', 'currency' => 'IDR'],
        ];

        $response = $this->postJson(
            route('webhooks.winpay.qris'),
            $payload,
            $this->signatureHeaders('/webhooks/winpay/v1.0/qr/qr-mpm-notify', $payload),
        );

        $response->assertBadRequest();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'unpaid',
        ]);
    }

    private function createCart(): void
    {
        $laptop = Laptop::factory()->create(['price' => 1600.00, 'stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cart->items()->create([
            'laptop_id' => $laptop->id,
            'quantity' => 1,
            'unit_price' => 1600.00,
        ]);
    }

    private function checkoutData(array $extra = []): array
    {
        return array_merge([
            'shipping_address' => 'Jl. Merdeka No. 123',
            'shipping_city' => 'Jakarta',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '12345',
            'shipping_phone' => '081234567890',
            'shipping_cost' => 50000,
            'shipping_courier' => 'jne',
            'shipping_service' => 'REG',
            'shipping_etd' => '2-3 hari',
            'shipping_city_id' => '152',
            'shipping_city_name' => 'Jakarta Selatan',
            'shipping_province_name' => 'DKI Jakarta',
        ], $extra);
    }

    private function createOrder(string $paymentMethod): Order
    {
        return Order::create([
            'user_id' => $this->user->id,
            'source' => 'online',
            'subtotal' => '100.00',
            'tax' => '0.00',
            'total' => '100.00',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'unpaid',
            'winpay_reference' => 'order-reference-'.Str::random(8),
        ]);
    }

    private function signatureHeaders(string $path, array $payload): array
    {
        $timestamp = now()->toIso8601String();
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $bodyHash = strtolower(hash('sha256', $body));
        $stringToSign = implode(':', ['POST', $path, $bodyHash, $timestamp]);

        $this->assertTrue(openssl_sign($stringToSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256));

        return [
            'X-Partner-ID' => 'test-partner',
            'X-Timestamp' => $timestamp,
            'X-Signature' => base64_encode($signature),
        ];
    }

    private function assertSignedRequest(string $path): void
    {
        $sent = Http::recorded(function (ClientRequest $request) use ($path): bool {
            if (! str_ends_with($request->url(), $path)) {
                return false;
            }

            $payload = json_decode($request->body(), true);
            $timestamp = $request->header('X-TIMESTAMP')[0] ?? '';
            $signature = $request->header('X-SIGNATURE')[0] ?? '';
            $partnerId = $request->header('X-PARTNER-ID')[0] ?? '';
            $bodyHash = strtolower(hash('sha256', $request->body()));
            $stringToSign = implode(':', ['POST', $path, $bodyHash, $timestamp]);
            $publicKey = openssl_pkey_get_public(file_get_contents($this->publicKeyPath));
            $payloadMatchesContract = $path === '/v1.0/qr/qr-mpm-generate'
                ? ($payload['partnerReferenceNo'] ?? null) === 'order-reference'
                    && ($payload['amount'] ?? null) === ['value' => '100.00', 'currency' => 'IDR']
                    && ($payload['additionalInfo']['isStatic'] ?? null) === false
                : ($payload['trxId'] ?? null) === 'order-reference'
                    && ($payload['totalAmount'] ?? null) === ['value' => '100.00', 'currency' => 'IDR']
                    && ($payload['virtualAccountTrxType'] ?? null) === 'c'
                    && ($payload['additionalInfo']['channel'] ?? null) === 'BCA';

            return $partnerId === 'test-partner'
                && $timestamp !== ''
                && is_string($signature)
                && $publicKey !== false
                && $payloadMatchesContract
                && openssl_verify($stringToSign, base64_decode($signature, true), $publicKey, OPENSSL_ALGO_SHA256) === 1;
        });

        $this->assertCount(1, $sent);
    }
}
