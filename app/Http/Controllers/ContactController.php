<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Setting;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

    public function index(): View
    {
        $storeInfo = [
            'name' => Setting::getValue('store_name', 'ZLM.ID Laptop Store'),
            'address' => Setting::getValue('store_address', 'Jl. Soekarno Hatta No. 45, Lowokwaru, Kota Malang, Jawa Timur 65141'),
            'phone' => Setting::getValue('store_phone', '+62 812-3456-7890'),
            'email' => Setting::getValue('store_email', 'contact@zlm.id'),
            'whatsapp' => Setting::getValue('store_whatsapp', '6281234567890'),
            'hours' => Setting::getValue('store_opening_hours', 'Senin - Minggu: 09:00 - 21:00 WIB'),
            'maps' => Setting::getValue('store_google_maps', ''),
            'instagram' => Setting::getValue('social_instagram', 'https://instagram.com/zlm.id'),
            'facebook' => Setting::getValue('social_facebook', 'https://facebook.com/zlm.id'),
            'tiktok' => Setting::getValue('social_tiktok', 'https://tiktok.com/@zlm.id'),
            'youtube' => Setting::getValue('social_youtube', 'https://youtube.com/@zlm.id'),
        ];

        return view('landing.contact', compact('storeInfo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $message = ContactMessage::create($validated);

        // Notify Admin via WhatsApp if configured
        $adminPhone = Setting::getValue('wa_admin_phone', '');
        if ($adminPhone) {
            $text = "📩 *PESAN KONTAK DARI WEBSITE*\n\n"
                . "• Nama: *{$message->name}*\n"
                . "• Email: {$message->email}\n"
                . "• No. HP: {$message->phone}\n"
                . "• Subjek: {$message->subject}\n"
                . "• Pesan:\n_{$message->message}_";
            $this->whatsAppService->sendMessage($adminPhone, $text);
        }

        return redirect()->back()
            ->with('success', 'Pesan Anda telah berhasil dikirim. Tim ZLM.ID akan segera menghubungi Anda kembali.');
    }
}
