import CacheManager from '../../../src/assets/js/modules/cache-manager';

describe('CacheManager', () => {
    let cacheManager;
    
    beforeEach(() => {
        // Mock Cache API
        global.caches = {
            open: jest.fn().mockResolvedValue({
                match: jest.fn(),
                put: jest.fn(),
                delete: jest.fn(),
                keys: jest.fn().mockResolvedValue([])
            }),
            keys: jest.fn().mockResolvedValue([]),
            delete: jest.fn()
        };
        
        cacheManager = new CacheManager();
    });

    test('initializes with correct cache names and config', () => {
        expect(cacheManager.staticCacheName).toContain('static-cache-v');
        expect(cacheManager.imageCacheName).toContain('image-cache-v');
        expect(cacheManager.fontCacheName).toContain('font-cache-v');
        expect(cacheManager.apiCacheName).toContain('api-cache-v');
        
        expect(cacheManager.cacheConfig.staticAssets.maxAge).toBeDefined();
        expect(cacheManager.cacheConfig.images.maxItems).toBeDefined();
    });

    test('correctly identifies asset types', () => {
        expect(cacheManager.isStaticAsset('style.css')).toBeTruthy();
        expect(cacheManager.isImage('photo.jpg')).toBeTruthy();
        expect(cacheManager.isFont('font.woff2')).toBeTruthy();
        expect(cacheManager.isApiRequest('/wp-json/api/v1/posts')).toBeTruthy();
    });

    test('clears old caches', async () => {
        global.caches.keys.mockResolvedValue([
            'static-cache-v0.9.0',
            'image-cache-v0.9.0',
            'current-cache'
        ]);

        await cacheManager.clearOldCaches();

        expect(global.caches.delete).toHaveBeenCalledWith('static-cache-v0.9.0');
        expect(global.caches.delete).toHaveBeenCalledWith('image-cache-v0.9.0');
        expect(global.caches.delete).not.toHaveBeenCalledWith('current-cache');
    });

    test('validates cache age correctly', () => {
        const mockResponse = new Response(null, {
            headers: new Headers({
                'x-cache-timestamp': Date.now() - (24 * 60 * 60 * 1000) // 1 day old
            })
        });
        mockResponse.url = 'test.css';

        expect(cacheManager.isCacheValid(mockResponse)).toBeTruthy();

        const oldResponse = new Response(null, {
            headers: new Headers({
                'x-cache-timestamp': Date.now() - (10 * 24 * 60 * 60 * 1000) // 10 days old
            })
        });
        oldResponse.url = 'test.css';

        expect(cacheManager.isCacheValid(oldResponse)).toBeFalsy();
    });

    test('manages cache size correctly', async () => {
        const mockCache = {
            keys: jest.fn().mockResolvedValue(Array(101).fill({ url: 'test.css' })),
            delete: jest.fn()
        };
        
        await cacheManager.cleanupCache(mockCache);
        
        // Should delete oldest entries to maintain max size
        expect(mockCache.delete).toHaveBeenCalledTimes(1);
    });

    test('updates cache with correct headers', async () => {
        const mockRequest = new Request('test.js');
        const mockResponse = new Response('test');
        const mockCache = {
            put: jest.fn()
        };
        
        global.caches.open.mockResolvedValue(mockCache);
        
        await cacheManager.updateCache(mockRequest, mockResponse);
        
        expect(mockCache.put).toHaveBeenCalled();
        const [, storedResponse] = mockCache.put.mock.calls[0];
        expect(storedResponse.headers.has('x-cache-timestamp')).toBeTruthy();
    });

    test('returns appropriate cache for different asset types', async () => {
        const staticRequest = new Request('test.js');
        const imageRequest = new Request('test.jpg');
        const fontRequest = new Request('test.woff2');
        const apiRequest = new Request('/wp-json/api/test');
        
        await cacheManager.getappropriateCache(staticRequest);
        expect(global.caches.open).toHaveBeenCalledWith(cacheManager.staticCacheName);
        
        await cacheManager.getappropriateCache(imageRequest);
        expect(global.caches.open).toHaveBeenCalledWith(cacheManager.imageCacheName);
        
        await cacheManager.getappropriateCache(fontRequest);
        expect(global.caches.open).toHaveBeenCalledWith(cacheManager.fontCacheName);
        
        await cacheManager.getappropriateCache(apiRequest);
        expect(global.caches.open).toHaveBeenCalledWith(cacheManager.apiCacheName);
    });
});