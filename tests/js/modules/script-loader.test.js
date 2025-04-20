import ScriptLoader from '../../../src/assets/js/modules/script-loader';

describe('ScriptLoader', () => {
    let scriptLoader;
    
    beforeEach(() => {
        // Mock dynamic imports
        global.import = jest.fn();
        
        // Mock link relList support
        Object.defineProperty(document.createElement('link'), 'relList', {
            value: { supports: jest.fn().mockReturnValue(true) }
        });
        
        scriptLoader = new ScriptLoader();
    });

    test('initializes with empty module maps', () => {
        expect(scriptLoader.loadedModules.size).toBe(0);
        expect(scriptLoader.modulePromises.size).toBe(0);
    });

    test('adds preload support correctly', () => {
        const mockLink = document.querySelector('link[rel="preload"]');
        expect(mockLink).toBeTruthy();
        expect(mockLink.getAttribute('as')).toBe('script');
    });

    test('sets up import map', () => {
        const importMap = document.querySelector('script[type="importmap"]');
        expect(importMap).toBeTruthy();
        const mapContent = JSON.parse(importMap.textContent);
        expect(mapContent.imports).toBeDefined();
        expect(Object.keys(mapContent.imports).length).toBeGreaterThan(0);
    });

    test('loads module with dependencies', async () => {
        const mockModule = { default: class TestModule {} };
        global.import = jest.fn().mockResolvedValue(mockModule);

        const module = await scriptLoader.loadModule('test-module', ['dependency']);
        expect(module).toBe(mockModule);
        expect(scriptLoader.loadedModules.get('test-module')).toBe(mockModule);
    });

    test('retries failed module loads', async () => {
        global.import = jest.fn()
            .mockRejectedValueOnce(new Error('Network error'))
            .mockResolvedValueOnce({ default: class TestModule {} });

        const module = await scriptLoader.loadWithRetry('test-module');
        expect(module).toBeDefined();
        expect(global.import).toHaveBeenCalledTimes(2);
    });

    test('prefetches modules', () => {
        scriptLoader.prefetchModule('test-module');
        const prefetchLink = document.querySelector('link[rel="prefetch"]');
        expect(prefetchLink).toBeTruthy();
        expect(prefetchLink.href).toContain('test-module');
    });

    test('initializes module with options', async () => {
        const mockModule = { 
            default: class TestModule {
                constructor(options) {
                    this.options = options;
                }
            }
        };
        global.import = jest.fn().mockResolvedValue(mockModule);

        const options = { test: true };
        const instance = await scriptLoader.initializeModule('test-module', options);
        expect(instance.options).toEqual(options);
    });

    test('caches loaded modules', async () => {
        const mockModule = { default: class TestModule {} };
        global.import = jest.fn().mockResolvedValue(mockModule);

        await scriptLoader.loadModule('test-module');
        await scriptLoader.loadModule('test-module');
        
        expect(global.import).toHaveBeenCalledTimes(1);
    });
});