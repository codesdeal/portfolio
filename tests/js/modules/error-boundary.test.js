import ErrorBoundary from '../../../src/assets/js/modules/error-boundary';

describe('ErrorBoundary', () => {
    let errorBoundary;
    let consoleSpy;

    beforeEach(() => {
        errorBoundary = new ErrorBoundary();
        consoleSpy = jest.spyOn(console, 'error').mockImplementation();
        
        // Mock fetch
        global.fetch = jest.fn();
        
        // Mock DOM methods
        document.body.appendChild = jest.fn();
        document.createElement = jest.fn().mockReturnValue({
            className: '',
            innerHTML: '',
            querySelector: jest.fn().mockReturnValue({
                addEventListener: jest.fn()
            })
        });
    });

    afterEach(() => {
        consoleSpy.mockRestore();
        jest.clearAllMocks();
    });

    test('initializes with empty error maps', () => {
        expect(errorBoundary.errors.size).toBe(0);
        expect(errorBoundary.retryAttempts.size).toBe(0);
    });

    test('handles runtime errors', () => {
        const error = new Error('Test error');
        window.onerror('Test error', 'test.js', 1, 1, error);

        expect(errorBoundary.errors.size).toBe(1);
        expect(consoleSpy).toHaveBeenCalled();
        expect(document.createElement).toHaveBeenCalledWith('div');
    });

    test('handles promise rejections', () => {
        const event = {
            reason: new Error('Promise error'),
            preventDefault: jest.fn()
        };

        window.onunhandledrejection(event);

        expect(errorBoundary.errors.size).toBe(1);
        expect(event.preventDefault).toHaveBeenCalled();
    });

    test('handles network errors', async () => {
        global.fetch.mockRejectedValueOnce(new Error('Network error'));

        try {
            await window.fetch('test-url');
        } catch (error) {
            expect(errorBoundary.errors.size).toBe(1);
            const errorEntry = Array.from(errorBoundary.errors.values())[0];
            expect(errorEntry.type).toBe('network');
        }
    });

    test('retries retryable errors', () => {
        jest.useFakeTimers();

        const networkError = {
            type: 'network',
            message: 'Failed to fetch',
            url: 'test-url',
            timestamp: Date.now()
        };

        errorBoundary.handleError(networkError);
        
        expect(errorBoundary.retryAttempts.size).toBe(1);
        expect(setTimeout).toHaveBeenCalled();

        jest.runAllTimers();
        expect(global.fetch).toHaveBeenCalledWith('test-url');
    });

    test('shows error UI for non-retryable errors', () => {
        const nonRetryableError = {
            type: 'runtime',
            message: 'Syntax error',
            timestamp: Date.now()
        };

        errorBoundary.handleError(nonRetryableError);

        expect(document.createElement).toHaveBeenCalledWith('div');
        expect(document.body.appendChild).toHaveBeenCalled();
    });

    test('calculates retry delay with exponential backoff', () => {
        const attempt1 = errorBoundary.calculateRetryDelay(1);
        const attempt2 = errorBoundary.calculateRetryDelay(2);

        expect(attempt2).toBeGreaterThan(attempt1);
        expect(attempt2).toBeLessThanOrEqual(10000); // maxDelay
    });

    test('cleans up old errors', () => {
        const oldError = {
            type: 'runtime',
            message: 'Old error',
            timestamp: Date.now() - (25 * 60 * 60 * 1000) // 25 hours old
        };

        const newError = {
            type: 'runtime',
            message: 'New error',
            timestamp: Date.now()
        };

        errorBoundary.handleError(oldError);
        errorBoundary.handleError(newError);
        
        errorBoundary.cleanupErrors();

        expect(errorBoundary.errors.size).toBe(1);
        const remainingError = Array.from(errorBoundary.errors.values())[0];
        expect(remainingError.message).toBe('New error');
    });

    test('formats error messages differently for development and production', () => {
        const error = {
            type: 'runtime',
            message: 'Test error',
            error: new Error('Test error')
        };

        const originalEnv = process.env.NODE_ENV;
        
        process.env.NODE_ENV = 'development';
        const devMessage = errorBoundary.formatErrorMessage(error);
        expect(devMessage).toContain('Test error');
        expect(devMessage).toContain('Error:');

        process.env.NODE_ENV = 'production';
        const prodMessage = errorBoundary.formatErrorMessage(error);
        expect(prodMessage).toBe('An unexpected error occurred. We\'re working to fix it.');

        process.env.NODE_ENV = originalEnv;
    });
});