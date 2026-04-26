const CACHE_NAME = 'v1.0.5';
const cacheAssets = [
    '/favicon.ico',
    '/css/fontawesome/css/all.min.css',
    '/css/fontawesome/webfonts/fa-solid-900.woff2',
    '/css/fontawesome/webfonts/fa-regular-400.woff2',
    '/css/fontawesome/webfonts/fa-brands-400.woff2'
];

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            cache.addAll(cacheAssets);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keyList => {
            return Promise.all(keyList.map(key => {
                if (key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            }));
        }).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    // Skip service worker for external CDNs and other origins
    // Using event.respondWith(fetch(event.request)) to ensure the browser handles it directly
    if (!event.request.url.startsWith(self.location.origin)) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        caches.match(event.request).then(response => {
            if (response) {
                return response;
            }
            return fetch(event.request).catch(err => {
                console.error('[SW] Fetch Error:', err);
                return new Response('Network error occurred', {
                    status: 408,
                    headers: { 'Content-Type': 'text/plain' }
                });
            });
        })
    );
});