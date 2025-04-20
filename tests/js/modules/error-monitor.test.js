import ErrorMonitor from '../../../src/assets/js/modules/error-monitor';

describe('ErrorMonitor', () => {
    let errorMonitor;
    let fetchMock;
    let originalPerformance;

    beforeEach(() => {
        jest.useFakeTimers();
        fetchMock = jest.fn();
        global.fetch = fetchMock;
        
        // Mock performance API
        originalPerformance = global.performance;
        global.performance = {
            getEntriesByType: jest.fn().mockReturnValue([]),
            timing: {
                toJSON: jest.fn().mockReturnValue({})
            },
            memory: {
                usedJSHeapSize: 1000000
            }
        };

        errorMonitor = new ErrorMonitor();
    });

    afterEach(() => {
        jest.clearAllTimers();
        jest.restoreAllMocks();
        global.performance = originalPerformance;
    });

    test('initializes with empty queue', () => {
        expect(errorMonitor.queue).toHaveLength(0);
    });

    test('captures error and adds to queue', () => {
        const testError = {
            type: 'runtime',
            message: 'Test error',
            error: new Error('Test error')
        };

        errorMonitor.captureError(testError);
        expect(errorMonitor.queue).toHaveLength(1);
        
        const queuedError = errorMonitor.queue[0];
        expect(queuedError.type).toBe('runtime');
        expect(queuedError.message).toBe('Test error');
        expect(queuedError.stack).toBeDefined();
    });

    test('flushes queue when max size is reached', () => {
        fetchMock.mockResolvedValueOnce({ ok: true });

        for (let i = 0; i < errorMonitor.maxQueueSize + 1; i++) {
            errorMonitor.captureError({
                type: 'test',
                message: `Error ${i}`
            });
        }

        expect(fetchMock).toHaveBeenCalled();
        expect(errorMonitor.queue).toHaveLength(1); // Last error remains in queue
    });

    test('automatically flushes on interval', () => {
        fetchMock.mockResolvedValueOnce({ ok: true });

        errorMonitor.captureError({
            type: 'test',
            message: 'Test error'
        });

        expect(fetchMock).not.toHaveBeenCalled();
        
        jest.advanceTimersByTime(errorMonitor.flushInterval);
        
        expect(fetchMock).toHaveBeenCalled();
        expect(errorMonitor.queue).toHaveLength(0);
    });

    test('retains errors in queue if flush fails', async () => {
        fetchMock.mockRejectedValueOnce(new Error('Network error'));

        const testError = {
            type: 'test',
            message: 'Test error'
        };

        errorMonitor.captureError(testError);
        await errorMonitor.flush();

        expect(errorMonitor.queue).toHaveLength(1);
        expect(errorMonitor.queue[0].message).toBe('Test error');
    });

    test('collects performance metrics when available', () => {
        const mockNavigation = {
            domComplete: 1000,
            domInteractive: 500,
            loadEventEnd: 1200,
            responseEnd: 800,
            responseStart: 200
        };

        const mockPaint = [
            { name: 'first-paint', startTime: 100 },
            { name: 'first-contentful-paint', startTime: 200 }
        ];

        performance.getEntriesByType.mockImplementation((type) => {
            switch(type) {
                case 'navigation':
                    return [mockNavigation];
                case 'paint':
                    return mockPaint;
                case 'resource':
                    return [];
                default:
                    return [];
            }
        });

        const testError = {
            type: 'test',
            message: 'Test error'
        };

        errorMonitor.captureError(testError);
        const queuedError = errorMonitor.queue[0];

        expect(queuedError.performance.navigation).toEqual({
            domComplete: 1000,
            domInteractive: 500,
            loadEventEnd: 1200,
            responseEnd: 800,
            responseStart: 200
        });

        expect(queuedError.performance.paint).toEqual({
            'first-paint': 100,
            'first-contentful-paint': 200
        });
    });

    test('handles missing performance API gracefully', () => {
        global.performance = undefined;

        const testError = {
            type: 'test',
            message: 'Test error'
        };

        errorMonitor.captureError(testError);
        const queuedError = errorMonitor.queue[0];

        expect(queuedError.performance).toBeNull();
    });
});