class ImageLoader {
    constructor() {
        this.options = {
            rootMargin: '50px 0px',
            threshold: 0.1
        };
        this.imageObserver = null;
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            this.imageObserver = new IntersectionObserver(
                this.handleIntersection.bind(this),
                this.options
            );
            this.observeImages();
        } else {
            this.loadImagesImmediately();
        }

        this.setupProgressiveLoading();
    }

    observeImages() {
        const images = document.querySelectorAll('img[data-src], source[data-srcset]');
        images.forEach(image => {
            this.imageObserver.observe(image);
        });
    }

    handleIntersection(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                this.loadImage(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }

    loadImage(image) {
        const src = image.dataset.src;
        const srcset = image.dataset.srcset;
        const sizes = image.dataset.sizes;

        if (src) {
            image.src = src;
            image.removeAttribute('data-src');
        }

        if (srcset) {
            image.srcset = srcset;
            image.removeAttribute('data-srcset');
        }

        if (sizes) {
            image.sizes = sizes;
            image.removeAttribute('data-sizes');
        }

        image.classList.add('is-loaded');
    }

    loadImagesImmediately() {
        const images = document.querySelectorAll('img[data-src], source[data-srcset]');
        Array.from(images).forEach(image => this.loadImage(image));
    }

    setupProgressiveLoading() {
        const progressiveImages = document.querySelectorAll('.c-progressive-image');
        progressiveImages.forEach(container => {
            const img = container.querySelector('img');
            const placeholder = container.querySelector('.c-progressive-image__placeholder');

            if (img && placeholder) {
                // Load high-quality image
                const highQualityImage = new Image();
                highQualityImage.src = img.dataset.src;
                highQualityImage.onload = () => {
                    img.src = highQualityImage.src;
                    container.classList.add('is-loaded');
                    setTimeout(() => {
                        placeholder.style.display = 'none';
                    }, 500);
                };
            }
        });
    }

    // Helper method to create blur-hash placeholder
    createPlaceholder(width, height, hash) {
        // Implementation would depend on the blur-hash library
        // This is just a placeholder for the concept
        return `data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 ${width} ${height}'%3E%3Cfilter id='b' color-interpolation-filters='sRGB'%3E%3CfeGaussianBlur stdDeviation='20'/%3E%3C/filter%3E%3Cimage preserveAspectRatio='none' filter='url(%23b)' x='0' y='0' height='100%25' width='100%25' href='${hash}'/%3E%3C/svg%3E`;
    }
}

export default ImageLoader;