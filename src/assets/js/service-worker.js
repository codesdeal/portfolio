const config = self.swConfig || {
    cacheName: '_themename_cache_v1',
    debugMode: false,
    offlinePage: '/offline/',
    cacheFirst: ['fonts', 'images', 'static']
};

// Cache patterns for different types of resources
const cachePatterns = {
    fonts: /\.(woff|woff2|ttf|eot)$/,
    images: /\.(jpg|jpeg|png|gif|svg|webp)$/,
    static: /\.(css|js)$/
};

// Resources to precache
const precacheResources = [
    config.offlinePage,
    '/wp-content/themes/portfolio/dist/assets/css/critical.css',
    '/wp-content/themes/portfolio/dist/assets/css/bundle.css',
    '/wp-content/themes/portfolio/dist/assets/js/bundle.js'
];

// Install event - precache critical resources
self.addEventListener('install', event => {
    event.waitUntil(precache());
});

// Activate event - cleanup old caches
self.addEventListener('activate', event => {
    event.waitUntil(cleanupOldCaches());
});

// Fetch event - handle requests
self.addEventListener('fetch', event => {
    const request = event.request;
    if (isFetchRequest(request)) {
        event.respondWith(handleFetchRequest(request));
    }
});

// Cache-first strategy for static assets
async function cacheFirstStrategy(request) {
    const cache = await caches.open(config.cacheName);
    const cachedResponse = await cache.match(request);

    if (cachedResponse) {
        debugLog('Cache hit:', request.url);
        return cachedResponse;
    }

    return fetchAndCache(request, cache);
}

// Network-first strategy for dynamic content
async function networkFirstStrategy(request) {
    try {
        const networkResponse = await fetch(request);
        cacheResponse(request, networkResponse);
        return networkResponse;
    } catch (error) {
        debugLog('Network request failed:', error);
        return handleOfflineResponse(request);
    }
}

// Handle offline response
async function handleOfflineResponse(request) {
    if (request.headers.get('Accept').includes('text/html')) {
        return caches.match(config.offlinePage);
    }

    return new Response('', {
        status: 408,
        statusText: 'Connection timed out.'
    });
}

// Cache and return the response
async function fetchAndCache(request, cache) {
    try {
        const networkResponse = await fetch(request);
        cacheResponse(request, networkResponse);
        return networkResponse;
    } catch (error) {
        debugLog('Fetch failed:', error);
        return await handleOfflineResponse(request);
    }
}

// Cache the response if valid
function cacheResponse(request, response) {
    if (shouldCache(request)) {
        const cache = caches.open(config.cacheName);
        cache.put(request, response.clone());
    }
}

// Check if resource should be cached
function shouldCache(request) {
    const url = new URL(request.url);
    return (
        url.origin === location.origin &&
        !url.pathname.startsWith('/wp-admin') &&
        !url.pathname.startsWith('/wp-login')
    );
}

// Check if resource should use cache-first strategy
function isCacheFirstResource(pathname) {
    return Object.values(cachePatterns).some(pattern => pattern.test(pathname));
}

// Check if the fetch request should be handled
function isFetchRequest(request) {
    return request.method === 'GET' && new URL(request.url).protocol !== 'chrome-extension:';
}

// Precache the necessary resources
async function precache() {
    const cache = await caches.open(config.cacheName);
    await cache.addAll(precacheResources);
    self.skipWaiting();
}

// Cleanup old caches
async function cleanupOldCaches() {
    const cacheNames = await caches.keys();
    await Promise.all(
        cacheNames.filter(name => name !== config.cacheName).map(name => caches.delete(name))
    );
    self.clients.claim();
}

// Handle fetch requests using the appropriate strategy
async function handleFetchRequest(request) {
    const url = new URL(request.url);
    if (isCacheFirstResource(url.pathname)) {
        return cacheFirstStrategy(request);
    } else {
        return networkFirstStrategy(request);
    }
}

// Debug logging
function debugLog(...args) {
    if (config.debugMode) {
        console.log('[ServiceWorker]', ...args);
    }
}

// Handle messages from the client
self.addEventListener('message', event => {
    if (event.data === 'skipWaiting') {
        self.skipWaiting();
    }
});
