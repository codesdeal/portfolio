import ImageLoader from '../../../src/assets/js/modules/image-loader';

describe('ImageLoader', () => {
    let imageLoader;
    
    beforeEach(() => {
        // Mock IntersectionObserver
        global.IntersectionObserver = jest.fn().mockImplementation((callback) => ({
            observe: jest.fn(),
            unobserve: jest.fn(),
            disconnect: jest.fn()
        }));

        document.body.innerHTML = `
            <div class="c-progressive-image">
                <div class="c-progressive-image__placeholder"></div>
                <img data-src="high-quality.jpg" src="placeholder.jpg" alt="Test image">
            </div>
            <img data-src="lazy-image.jpg" data-srcset="lazy-image-2x.jpg 2x" alt="Lazy load test">
        `;
        
        imageLoader = new ImageLoader();
    });

    test('initializes with correct configuration', () => {
        expect(imageLoader.options.rootMargin).toBe('50px 0px');
        expect(imageLoader.options.threshold).toBe(0.1);
        expect(imageLoader.imageObserver).toBeDefined();
    });

    test('handles image loading correctly', () => {
        const image = document.querySelector('img[data-src="lazy-image.jpg"]');
        imageLoader.loadImage(image);
        
        expect(image.src).toContain('lazy-image.jpg');
        expect(image.srcset).toBe('lazy-image-2x.jpg 2x');
        expect(image.hasAttribute('data-src')).toBeFalsy();
        expect(image.classList.contains('is-loaded')).toBeTruthy();
    });

    test('sets up progressive loading', () => {
        const container = document.querySelector('.c-progressive-image');
        const img = container.querySelector('img');
        const placeholder = container.querySelector('.c-progressive-image__placeholder');
        
        expect(placeholder).toBeDefined();
        expect(img.dataset.src).toBe('high-quality.jpg');
        
        // Simulate image load
        const loadEvent = new Event('load');
        const highQualityImage = new Image();
        highQualityImage.dispatchEvent(loadEvent);
        
        // Wait for the transition
        jest.advanceTimersByTime(500);
        
        expect(container.classList.contains('is-loaded')).toBeTruthy();
    });

    test('creates valid placeholder SVG', () => {
        const placeholder = imageLoader.createPlaceholder(400, 300, 'test-hash');
        expect(placeholder).toContain('svg');
        expect(placeholder).toContain('400');
        expect(placeholder).toContain('300');
        expect(placeholder).toContain('feGaussianBlur');
    });

    test('falls back gracefully when IntersectionObserver is not available', () => {
        // Remove IntersectionObserver
        delete global.IntersectionObserver;
        
        const fallbackLoader = new ImageLoader();
        const images = document.querySelectorAll('img[data-src]');
        
        images.forEach(img => {
            expect(img.src).toBeDefined();
            expect(img.hasAttribute('data-src')).toBeFalsy();
        });
    });
});