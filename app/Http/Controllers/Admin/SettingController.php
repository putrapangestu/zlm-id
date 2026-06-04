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
        return view('admin.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('tax_rate', $validated['tax_rate']);

        // Refresh config
        $settings = Setting::pluck('value', 'key');
        config(['settings' => $settings->toArray()]);

        return redirect()->route('admin.settings.index')->with('success', 'Settings saved successfully.');
    }
}
