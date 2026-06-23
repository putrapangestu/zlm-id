# PROFIL-1: Admin Profil ZLM (Store Info)

## Tujuan
Admin bisa mengatur informasi toko secara lengkap termasuk profil, sosial media, dan lokasi.

## Implementasi

### 1. Seed Default Settings
`database/seeders/SettingsSeeder.php` atau tambah ke seeder yang ada:

```php
$settings = [
    'store_name' => 'ZLM.ID',
    'store_description' => 'Premium laptop store — engineered excellence for professionals, creators, and gamers.',
    'store_address' => 'Jl. Raya Malang No. 123, Malang, Jawa Timur',
    'store_phone' => '+62 123 4567 8910',
    'store_email' => 'support@zlm.id',
    'store_google_maps' => '',
    'store_whatsapp' => '6212345678910',
    'social_instagram' => 'https://instagram.com/zlm.id',
    'social_facebook' => 'https://facebook.com/zlm.id',
    'social_tiktok' => 'https://tiktok.com/@zlm.id',
    'social_youtube' => 'https://youtube.com/@zlm.id',
    'store_logo' => '',
    'store_opening_hours' => 'Sen - Sab: 09:00 - 18:00',
];
```

### 2. Update SettingController
`app/Http/Controllers/Admin/SettingController.php`

Methods:
- `index()` — Tampilkan semua settings dengan tab
- `update(Request)` — Simpan settings

### 3. Update View: `resources/views/admin/settings/index.blade.php`

Layout dengan tabs:
```
┌─────────────────────────────────────────────┐
│ Settings                                     │
├─────────────────────────────────────────────┤
│ [General] [Sosial Media] [Lokasi]           │
├─────────────────────────────────────────────┤
│                                             │
│ === TAB GENERAL ===                         │
│ Nama Toko:    [ZLM.ID                 ]     │
│ Deskripsi:    [..............................]│
│ Email:        [support@zlm.id         ]     │
│ Telepon:      [+62 123 4567 8910      ]     │
│ Jam Operasi:  [Sen - Sab: 09-18       ]     │
│ Logo:         [Upload Image]               │
│                                             │
│ === TAB SOSIAL MEDIA ===                    │
│ Instagram:    [https://instagram...   ]     │
│ Facebook:     [https://facebook.com...]     │
│ TikTok:       [https://tiktok.com...  ]     │
│ YouTube:      [https://youtube.com... ]     │
│ WhatsApp:     [6212345678910          ]     │
│                                             │
│ === TAB LOKASI ===                          │
│ Alamat:       [Jl. Raya Malang No... ]     │
│ Google Maps:  [<iframe embed code>   ]     │
│                                             │
│ [Save Settings]                             │
└─────────────────────────────────────────────┘
```

### 4. Validasi
- URLs valid untuk sosial media
- Nomor telepon/WhatsApp valid
- Image upload: max 2MB, jpg/png/webp

## Definisi Selesai
- [x] Settings page dengan tabs (General, Sosial Media, Lokasi)
- [x] Semua field tersimpan di database
- [x] Validasi input berfungsi
- [x] Logo upload berfungsi
