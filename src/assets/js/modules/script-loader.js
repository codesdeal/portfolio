class ScriptLoader {
    constructor() {
        this.loadedModules = new Map();
        this.modulePromises = new Map();
        this.init();
    }

    init() {
        // Add support for module preloading
        this.addPreloadSupport();
        // Initialize dynamic import mapping
        this.setupImportMap();
    }

    addPreloadSupport() {
        // Add preload support for older browsers
        const preloadSupport = document.createElement('link').relList;
        if (preloadSupport && preloadSupport.supports('preload')) {
            this.preloadScripts();
        }
    }

    preloadScripts() {
        // Preload critical modules
        const criticalModules = [
            '/wp-content/themes/portfolio/dist/assets/js/keyboard-navigation.js',
            '/wp-content/themes/portfolio/dist/assets/js/image-loader.js'
        ];

        criticalModules.forEach(module => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'script';
            link.href = module;
            document.head.appendChild(link);
        });
    }

    setupImportMap() {
        // Create import map for module aliases
        const importMap = {
            imports: {
                'keyboard-navigation': '/wp-content/themes/portfolio/dist/assets/js/modules/keyboard-navigation.js',
                'image-loader': '/wp-content/themes/portfolio/dist/assets/js/modules/image-loader.js',
                'push-notifications': '/wp-content/themes/portfolio/dist/assets/js/modules/push-notifications.js'
            }
        };

        const script = document.createElement('script');
        script.type = 'importmap';
        script.textContent = JSON.stringify(importMap);
        document.head.appendChild(script);
    }

    async loadModule(moduleName, dependencies = []) {
        // Return cached module if already loaded
        if (this.loadedModules.has(moduleName)) {
            return this.loadedModules.get(moduleName);
        }

        // Return existing promise if module is loading
        if (this.modulePromises.has(moduleName)) {
            return this.modulePromises.get(moduleName);
        }

        // Load dependencies first
        const dependencyPromises = dependencies.map(dep => this.loadModule(dep));

        const modulePromise = (async () => {
            try {
                // Wait for dependencies
                await Promise.all(dependencyPromises);

                // Dynamic import of the module
                const module = await import(moduleName);
                this.loadedModules.set(moduleName, module);
                this.modulePromises.delete(moduleName);
                return module;
            } catch (error) {
                console.error(`Failed to load module ${moduleName}:`, error);
                this.modulePromises.delete(moduleName);
                throw error;
            }
        })();

        this.modulePromises.set(moduleName, modulePromise);
        return modulePromise;
    }

    async loadWithRetry(moduleName, retries = 3) {
        for (let i = 0; i < retries; i++) {
            try {
                return await this.loadModule(moduleName);
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * Math.pow(2, i)));
            }
        }
    }

    prefetchModule(moduleName) {
        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.as = 'script';
        link.href = moduleName;
        document.head.appendChild(link);
    }

    async initializeModule(moduleName, options = {}) {
        const module = await this.loadModule(moduleName);
        if (module.default && typeof module.default === 'function') {
            return new module.default(options);
        }
        return module;
    }
}

export default ScriptLoader;