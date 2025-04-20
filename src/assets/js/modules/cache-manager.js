class CacheManager {
    constructor() {
        this.version = '1.0.0';
        this.staticCacheName = `static-cache-v${this.version}`;
        this.imageCacheName = `image-cache-v${this.version}`;
        this.fontCacheName = `font-cache-v${this.version}`;
        this.apiCacheName = `api-cache-v${this.version}`;
        
        this.cacheConfig = {
            staticAssets: {
                maxAge: 7 * 24 * 60 * 60, // 7 days
                maxItems: 100
            },
            images: {
                maxAge: 30 * 24 * 60 * 60, // 30 days
                maxItems: 50
            },
            fonts: {
                maxAge: 90 * 24 * 60 * 60, // 90 days
                maxItems: 20
            },
            api: {
                maxAge: 60 * 60, // 1 hour
                maxItems: 50
            }
        };
    }

    async initCache() {
        if ('caches' in window) {
            // Clear old caches
            await this.clearOldCaches();
            
            // Precache critical assets
            await this.precacheAssets();
            
            // Set cache headers
            this.setCacheHeaders();
        }
    }

    async clearOldCaches() {
        const cacheNames = await caches.keys();
        const oldCaches = cacheNames.filter(name => 
            name.startsWith('static-cache-') && name !== this.staticCacheName ||
            name.startsWith('image-cache-') && name !== this.imageCacheName ||
            name.startsWith('font-cache-') && name !== this.fontCacheName ||
            name.startsWith('api-cache-') && name !== this.apiCacheName
        );
        
        return Promise.all(oldCaches.map(cache => caches.delete(cache)));
    }

    async precacheAssets() {
        const criticalAssets = [
            '/wp-content/themes/portfolio/dist/assets/css/critical.css',
            '/wp-content/themes/portfolio/dist/assets/js/bundle.js',
            '/offline.html'
        ];

        const cache = await caches.open(this.staticCacheName);
        return cache.addAll(criticalAssets);
    }

    setCacheHeaders() {
        // Set cache-control headers for different asset types
        const headers = new Headers();
        
        // Static assets (CSS, JS)
        headers.append('Cache-Control', `public, max-age=${this.cacheConfig.staticAssets.maxAge}`);
        
        // Images
        if (this.isImage(request.url)) {
            headers.append('Cache-Control', `public, max-age=${this.cacheConfig.images.maxAge}`);
        }
        
        // Fonts
        if (this.isFont(request.url)) {
            headers.append('Cache-Control', `public, max-age=${this.cacheConfig.fonts.maxAge}`);
        }
        
        // API responses
        if (this.isApiRequest(request.url)) {
            headers.append('Cache-Control', `public, max-age=${this.cacheConfig.api.maxAge}`);
        }
    }

    async getCacheResponse(request) {
        const cache = await this.getappropriateCache(request);
        const cachedResponse = await cache.match(request);
        
        if (cachedResponse) {
            // Check if cache is still valid
            if (this.isCacheValid(cachedResponse)) {
                return cachedResponse;
            } else {
                // Remove expired cache
                cache.delete(request);
            }
        }
        
        return null;
    }

    async updateCache(request, response) {
        const cache = await this.getappropriateCache(request);
        const clonedResponse = response.clone();
        
        // Add timestamp for cache validation
        const headers = new Headers(clonedResponse.headers);
        headers.append('x-cache-timestamp', Date.now());
        
        const modifiedResponse = new Response(clonedResponse.body, {
            status: clonedResponse.status,
            statusText: clonedResponse.statusText,
            headers: headers
        });
        
        await cache.put(request, modifiedResponse);
        
        // Cleanup old cache entries if needed
        await this.cleanupCache(cache);
    }

    async getappropriateCache(request) {
        const url = request.url;
        
        if (this.isStaticAsset(url)) {
            return await caches.open(this.staticCacheName);
        } else if (this.isImage(url)) {
            return await caches.open(this.imageCacheName);
        } else if (this.isFont(url)) {
            return await caches.open(this.fontCacheName);
        } else if (this.isApiRequest(url)) {
            return await caches.open(this.apiCacheName);
        }
        
        return await caches.open(this.staticCacheName);
    }

    isCacheValid(response) {
        const timestamp = response.headers.get('x-cache-timestamp');
        if (!timestamp) return false;
        
        const age = (Date.now() - parseInt(timestamp)) / 1000;
        const cacheType = this.getCacheType(response.url);
        
        return age < this.cacheConfig[cacheType].maxAge;
    }

    async cleanupCache(cache) {
        const entries = await cache.keys();
        const cacheType = this.getCacheType(entries[0].url);
        const maxItems = this.cacheConfig[cacheType].maxItems;
        
        if (entries.length > maxItems) {
            const itemsToDelete = entries.slice(0, entries.length - maxItems);
            await Promise.all(itemsToDelete.map(entry => cache.delete(entry)));
        }
    }

    getCacheType(url) {
        if (this.isImage(url)) return 'images';
        if (this.isFont(url)) return 'fonts';
        if (this.isApiRequest(url)) return 'api';
        return 'staticAssets';
    }

    isStaticAsset(url) {
        return /\.(css|js)$/.test(url);
    }

    isImage(url) {
        return /\.(jpg|jpeg|png|gif|webp|svg)$/.test(url);
    }

    isFont(url) {
        return /\.(woff|woff2|ttf|eot)$/.test(url);
    }

    isApiRequest(url) {
        return url.includes('/wp-json/');
    }
}

export default CacheManager;