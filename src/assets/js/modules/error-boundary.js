class ErrorBoundary {
    constructor() {
        this.errors = new Map();
        this.retryAttempts = new Map();
        this.maxRetries = 3;
        this.init();
    }

    init() {
        this.setupGlobalErrorHandler();
        this.setupPromiseErrorHandler();
        this.setupNetworkErrorHandler();
    }

    setupGlobalErrorHandler() {
        window.onerror = (message, source, lineno, colno, error) => {
            this.handleError({
                type: 'runtime',
                message,
                source,
                lineno,
                colno,
                error,
                timestamp: Date.now()
            });
            return true; // Prevent default error handling
        };
    }

    setupPromiseErrorHandler() {
        window.onunhandledrejection = (event) => {
            this.handleError({
                type: 'promise',
                message: event.reason?.message || 'Unhandled Promise Rejection',
                error: event.reason,
                timestamp: Date.now()
            });
            event.preventDefault();
        };
    }

    setupNetworkErrorHandler() {
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            try {
                const response = await originalFetch(...args);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response;
            } catch (error) {
                this.handleError({
                    type: 'network',
                    message: error.message,
                    url: args[0],
                    timestamp: Date.now(),
                    error
                });
                throw error;
            }
        };
    }

    handleError(errorInfo) {
        const errorKey = this.generateErrorKey(errorInfo);
        this.errors.set(errorKey, errorInfo);

        // Log error to console in development
        if (process.env.NODE_ENV === 'development') {
            console.error('Error caught by ErrorBoundary:', errorInfo);
        }

        // Track retry attempts
        const attempts = this.retryAttempts.get(errorKey) || 0;
        
        if (attempts < this.maxRetries && this.isRetryableError(errorInfo)) {
            this.retryAttempts.set(errorKey, attempts + 1);
            this.retryOperation(errorInfo);
        } else {
            this.showErrorUI(errorInfo);
        }

        // Clean up old errors
        this.cleanupErrors();
    }

    generateErrorKey(errorInfo) {
        return `${errorInfo.type}-${errorInfo.message}-${errorInfo.timestamp}`;
    }

    isRetryableError(errorInfo) {
        // Network errors are generally retryable
        if (errorInfo.type === 'network') {
            return true;
        }

        // Specific error types that we know are safe to retry
        const retryableErrors = [
            'Failed to fetch',
            'Network request failed',
            'TimeoutError',
            'The user aborted a request'
        ];

        return retryableErrors.some(error => 
            errorInfo.message?.includes(error)
        );
    }

    async retryOperation(errorInfo) {
        const delay = this.calculateRetryDelay(
            this.retryAttempts.get(this.generateErrorKey(errorInfo))
        );

        await new Promise(resolve => setTimeout(resolve, delay));

        if (errorInfo.type === 'network' && errorInfo.url) {
            try {
                await fetch(errorInfo.url);
            } catch (error) {
                // Error will be handled by setupNetworkErrorHandler
            }
        }
    }

    calculateRetryDelay(attempt) {
        // Exponential backoff with jitter
        const baseDelay = 1000; // 1 second
        const maxDelay = 10000; // 10 seconds
        const exponentialDelay = baseDelay * Math.pow(2, attempt);
        const jitter = Math.random() * 1000; // Random delay between 0-1000ms
        
        return Math.min(exponentialDelay + jitter, maxDelay);
    }

    showErrorUI(errorInfo) {
        const errorContainer = document.createElement('div');
        errorContainer.className = 'c-error-boundary';
        errorContainer.innerHTML = `
            <div class="c-error-boundary__content">
                <h2 class="c-error-boundary__title">Something went wrong</h2>
                <p class="c-error-boundary__message">
                    ${this.formatErrorMessage(errorInfo)}
                </p>
                ${this.isRetryableError(errorInfo) ? `
                    <button class="c-error-boundary__retry">
                        Try Again
                    </button>
                ` : ''}
                <button class="c-error-boundary__close">
                    Dismiss
                </button>
            </div>
        `;

        // Add event listeners
        const retryButton = errorContainer.querySelector('.c-error-boundary__retry');
        if (retryButton) {
            retryButton.addEventListener('click', () => {
                this.retryOperation(errorInfo);
                errorContainer.remove();
            });
        }

        const closeButton = errorContainer.querySelector('.c-error-boundary__close');
        closeButton.addEventListener('click', () => {
            errorContainer.remove();
        });

        document.body.appendChild(errorContainer);
    }

    formatErrorMessage(errorInfo) {
        if (process.env.NODE_ENV === 'development') {
            return `${errorInfo.message}\n${errorInfo.error?.stack || ''}`;
        }

        // User-friendly messages in production
        const friendlyMessages = {
            network: 'There was a problem connecting to the server. Please check your internet connection.',
            runtime: 'An unexpected error occurred. We\'re working to fix it.',
            promise: 'Something went wrong while loading the content.'
        };

        return friendlyMessages[errorInfo.type] || 'An error occurred.';
    }

    cleanupErrors() {
        const maxErrors = 50;
        const now = Date.now();
        const maxAge = 24 * 60 * 60 * 1000; // 24 hours

        // Remove old errors
        for (const [key, error] of this.errors) {
            if (now - error.timestamp > maxAge) {
                this.errors.delete(key);
                this.retryAttempts.delete(key);
            }
        }

        // Remove excess errors if we have too many
        const errorKeys = Array.from(this.errors.keys());
        if (errorKeys.length > maxErrors) {
            const keysToRemove = errorKeys.slice(0, errorKeys.length - maxErrors);
            keysToRemove.forEach(key => {
                this.errors.delete(key);
                this.retryAttempts.delete(key);
            });
        }
    }
}

export default ErrorBoundary;