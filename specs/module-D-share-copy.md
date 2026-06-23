# Spec Modul D — Share & Copy Link Buttons

## Problem
Tombol share (solar:share-circle-linear) dan copy link (solar:link-linear) di detail page tidak memiliki event handler. Klik tombol tidak melakukan apa-apa.

## Solusi

### 1. Tambah Event Handler di Detail Page

Di `resources/views/landing/detail.blade.php`, dalam tag `<script>` di bagian bawah:

```javascript
// ===== Share & Copy Functions =====
function shareProduct() {
    const url = window.location.href;
    const title = document.title;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Lihat produk ini di ZLM.ID',
            url: url,
        })
        .then(() => showToast('Berhasil dibagikan', 'success'))
        .catch((err) => {
            if (err.name !== 'AbortError') {
                copyToClipboard(url);
            }
        });
    } else {
        copyToClipboard(url);
    }
}

function copyProductLink() {
    const url = window.location.href;
    copyToClipboard(url);
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text)
            .then(() => showToast('Link disalin ke clipboard!', 'success'))
            .catch(() => fallbackCopy(text));
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showToast('Link disalin ke clipboard!', 'success');
    } catch (e) {
        showToast('Gagal menyalin link', 'error');
    }
    document.body.removeChild(textarea);
}
```

### 2. Update HTML Tombol

```blade
<button onclick="shareProduct()" class="w-10 h-10 rounded-full ..." title="Bagikan">
    <iconify-icon icon="solar:share-circle-linear" class="text-lg"></iconify-icon>
</button>
<button onclick="copyProductLink()" class="w-10 h-10 rounded-full ..." title="Salin Link">
    <iconify-icon icon="solar:link-linear" class="text-lg"></iconify-icon>
</button>
```

### 3. CSS Tooltip

Tambahkan sedikit CSS untuk tooltip agar user tahu fungsi tombol:

```css
/* Existing style block atau inline */
button[title]:hover::after {
    /* optional: tooltip native sudah pakai title attribute */
}
```

## Files Changed

| File | Action |
|------|--------|
| `resources/views/landing/detail.blade.php` | MODIFY (tambah onclick + fungsi JS) |

## Testing

- Klik tombol share → Web Share API terbuka (di mobile/Https) atau fallback copy
- Klik tombol copy → link tercopy ke clipboard + toast "Link disalin ke clipboard!"
- Paste link → URL detail product yang benar
- Browser tanpa clipboard API → fallback execCommand berfungsi
