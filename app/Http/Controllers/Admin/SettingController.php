<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
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
                    'store_google_maps' => 'nullable|string|max:1000',
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
            ->with('success', 'Settings updated successfully.');
    }
}
