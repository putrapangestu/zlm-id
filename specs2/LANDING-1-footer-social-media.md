# LANDING-1: Sosial Media & Lokasi Toko di Footer

## Tujuan
Footer landing page menampilkan informasi sosial media dan lokasi toko secara dinamis dari database settings.

## Implementasi

### 1. Update View: `resources/views/components/landing-footer.blade.php`

Footer baru dengan layout:

```
┌─────────────────────────────────────────────────────────┐
│ ZLM.ID      | Links          | Contact      | Ikuti Kami│
│             |                |              |           │
│ Deskripsi   | • Katalog      | support@zlm  | [IG][FB]  │
│ toko        | • Artikel      | +62 123...   | [TT][YT]  │
│             | • Bandingkan   |              | [WA]      │
│             | • Testimoni    |              |           │
│             |                |              |           │
├─────────────────────────────────────────────────────────┤
│ 📍 Jl. Raya Malang No. 123, Malang                      │
│ 🕐 Sen - Sab: 09:00 - 18:00                             │
├─────────────────────────────────────────────────────────┤
│ [Google Maps Embed]                                      │
├─────────────────────────────────────────────────────────┤
│ © 2026 ZLM.ID. All rights reserved.                     │
└─────────────────────────────────────────────────────────┘
```

### 2. Social Media Icons
Gunakan Iconify icons untuk setiap platform:
- Instagram → `solar:instagram-linear`
- Facebook → `solar:facebook-linear`
- TikTok → `solar:tiktok-linear`
- YouTube → `solar:youtube-linear`
- WhatsApp → `solar:phone-calling-linear`

Styling: Hover effect dengan warna brand masing-masing platform.

### 3. Google Maps Embed
Jika `store_google_maps` diisi, tampilkan iframe map.  
Gunakan Google Maps embed URL: `https://www.google.com/maps?q={alamat}&output=embed`

### 4. Dynamic Data
Semua data bersumber dari `config('settings.xxx')` yang sudah di-load di AppServiceProvider.

### 5. Add Link ke Testimonials
Tambah link "Testimoni" di navigasi footer.

## Definisi Selesai
- [x] Social media icons (IG, FB, TT, YT, WA) dari settings
- [x] Google Maps embed lokasi toko
- [x] Alamat & jam operasional dari settings
- [x] Hover effects pada social icons
- [x] Link Testimoni di navigasi footer
