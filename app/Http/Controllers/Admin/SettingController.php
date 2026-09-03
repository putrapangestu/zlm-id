<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

    public function index(): View
    {
        $tab = request('tab', 'general');
        return view('admin.settings.index', compact('tab'));
    }

    public function update(Request $request): RedirectResponse
    {
        $tab = $request->input('_tab', 'general');

        switch ($tab) {
            case 'general':
                $validated = $request->validate([
                    'store_name' => 'required|string|max:255',
                    'store_description' => 'nullable|string|max:1000',
                    'store_email' => 'nullable|email|max:255',
                    'store_phone' => 'nullable|string|max:50',
                    'store_opening_hours' => 'nullable|string|max:255',
                    'tax_rate' => 'required|numeric|min:0|max:100',
                    'store_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                ]);
                foreach ($validated as $key => $value) {
                    if ($key === 'store_logo' && $request->hasFile('store_logo')) {
                        $path = $request->file('store_logo')->store('settings', 'public');
                        Setting::setValue($key, $path);
                        continue;
                    }
                    Setting::setValue($key, $value);
                }
                break;

            case 'social':
                $validated = $request->validate([
                    'social_instagram' => 'nullable|url|max:255',
                    'social_facebook' => 'nullable|url|max:255',
                    'social_tiktok' => 'nullable|url|max:255',
                    'social_youtube' => 'nullable|url|max:255',
                    'store_whatsapp' => 'nullable|string|max:20',
                ]);
                foreach ($validated as $key => $value) {
                    Setting::setValue($key, $value);
                }
                break;

            case 'location':
                $validated = $request->validate([
                    'store_address' => 'nullable|string|max:500',
                    'store_google_maps' => 'nullable|string|max:2000',
                ]);
                foreach ($validated as $key => $value) {
                    Setting::setValue($key, $value);
                }
                break;

            case 'whatsapp':
                $validated = $request->validate([
                    'wa_notification_enabled' => 'nullable|in:0,1',
                    'wa_provider' => 'required|in:fonnte,wablas,generic',
                    'wa_api_token' => 'nullable|string|max:255',
                    'wa_admin_phone' => 'nullable|string|max:30',
                    'wa_notify_order_created' => 'nullable|in:0,1',
                    'wa_notify_payment_success' => 'nullable|in:0,1',
                    'wa_notify_order_shipped' => 'nullable|in:0,1',
                    'wa_notify_restock' => 'nullable|in:0,1',
                    'wa_notify_return_status' => 'nullable|in:0,1',
                ]);

                // Ensure toggles are saved as 1 or 0
                $toggles = [
                    'wa_notification_enabled',
                    'wa_notify_order_created',
                    'wa_notify_payment_success',
                    'wa_notify_order_shipped',
                    'wa_notify_restock',
                    'wa_notify_return_status',
                ];

                foreach ($toggles as $toggleKey) {
                    Setting::setValue($toggleKey, $request->has($toggleKey) ? '1' : '0');
                }

                Setting::setValue('wa_provider', $validated['wa_provider']);
                Setting::setValue('wa_api_token', $validated['wa_api_token'] ?? '');
                Setting::setValue('wa_admin_phone', $validated['wa_admin_phone'] ?? '');
                break;

            case 'dotmatrix':
                $validated = $request->validate([
                    'dotmatrix_header' => 'nullable|string|max:255',
                    'dotmatrix_address' => 'nullable|string|max:500',
                    'dotmatrix_footer' => 'nullable|string|max:500',
                    'dotmatrix_paper_width' => 'nullable|string|max:50',
                ]);
                foreach ($validated as $key => $value) {
                    Setting::setValue($key, $value);
                }
                break;
        }

        // Refresh settings config
        $settings = Setting::pluck('value', 'key');
        config(['settings' => $settings->toArray()]);

        return redirect()->route('admin.settings.index', ['tab' => $tab])
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function testWhatsApp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|max:30',
            'message' => 'required|string|max:500',
        ]);

        $result = $this->whatsAppService->sendMessage($request->phone, $request->message);

        return response()->json($result);
    }
}
