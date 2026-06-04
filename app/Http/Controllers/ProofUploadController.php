<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProofUploadController extends Controller
{
    public function upload(Request $request, Order $order): RedirectResponse
    {
        // Authorization: hanya pemilik
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini.');
        }

        // Validasi: hanya untuk manual_transfer yang unpaid
        if ($order->payment_method !== 'manual_transfer') {
            return redirect()->back()->with('error', 'Pembayaran ini tidak menggunakan transfer manual.');
        }
        if ($order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Pembayaran sudah diproses.');
        }

        // Validasi file
        $validated = $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'proof.required' => 'Silakan pilih file bukti transfer.',
            'proof.mimes' => 'File harus berupa JPG, JPEG, PNG, atau PDF.',
            'proof.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Simpan file
        $path = $request->file('proof')->store('proof-of-transfer', 'public');

        // Update order
        $order->update([
            'proof_of_transfer' => $path,
            'payment_status' => 'pending_verification',
        ]);

        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload. Menunggu verifikasi admin.');
    }
}
