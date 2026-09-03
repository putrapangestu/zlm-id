const CACHE_NAME = 'zlm-pos-cache-v1';
const ASSETS_TO_CACHE = [
    '/pos',
    '/pos-manifest.json',
    '/js/pos-db.js',
    '/js/pos-app.js',
    '/assets/logo.png',
    'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4',
    'https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[Service Worker] Caching App Shell & Assets for Offline POS');
            return cache.addAll(ASSETS_TO_CACHE).catch(err => console.warn('Cache prefetch error:', err));
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(
                keyList.map((key) => {
                    if (key !== CACHE_NAME) {
                        console.log('[Service Worker] Removing old cache:', key);
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    return self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Skip caching for POST requests (sync endpoint handled in app)
    if (event.request.method !== 'GET') {
        return;
    }

    // Network-first for bootstrap data with cache fallback
    if (url.pathname.includes('/pos/bootstrap')) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    if (response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
                    }
                    return response;
                })
                .catch(() => caches.match(event.request))
        );
        return;
    }

    // Cache-first for static assets
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }
            return fetch(event.request).then((response) => {
                if (!response || response.status !== 200 || response.type !== 'basic') {
                    return response;
                }
                const responseToCache = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, responseToCache);
                });
                return response;
            });
        })
    );
});
