const CACHE = 'cazeer-v2';
const STATIC_ASSETS = ['/offline', '/manifest.webmanifest', '/favicon.ico', '/assets/css/style.css', '/assets/css/pos.css', '/assets/vendors/css/vendor.bundle.base.css', '/assets/vendors/js/vendor.bundle.base.js', '/assets/vendors/mdi/css/materialdesignicons.min.css', '/assets/vendors/datatables.net/js/dataTables.min.js', '/assets/vendors/datatables.net-bs5/css/dataTables.bootstrap5.min.css', '/assets/vendors/datatables.net-bs5/js/dataTables.bootstrap5.min.js', '/assets/images/logos/cazeer-brand.png', '/assets/images/logos/cazeer-wordmark.png', '/assets/images/logos/cazeer-icon-clean.png', '/assets/images/icons/icon-192x192.png', '/assets/images/icons/icon-512x512.png', '/assets/images/icons/maskable-icon-512x512.png'];

self.addEventListener('install', event => event.waitUntil(
  caches.open(CACHE).then(cache => cache.addAll(STATIC_ASSETS)).then(() => self.skipWaiting())
));

self.addEventListener('activate', event => event.waitUntil(
  caches.keys().then(keys => Promise.all(keys.filter(key => key !== CACHE).map(key => caches.delete(key)))).then(() => self.clients.claim())
));

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;
  if (event.request.mode === 'navigate') {
    event.respondWith(fetch(event.request).catch(() => caches.match('/offline')));
    return;
  }
  if (new URL(event.request.url).origin === self.location.origin) {
    event.respondWith(caches.match(event.request).then(cached => cached || fetch(event.request).then(response => {
      const copy = response.clone();
      caches.open(CACHE).then(cache => cache.put(event.request, copy));
      return response;
    })));
  }
});
