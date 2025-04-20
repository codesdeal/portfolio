class ErrorMonitor {
    constructor() {
        this.queue = [];
        this.maxQueueSize = 50;
        this.flushInterval = 30000; // 30 seconds
        this.init();
    }

    init() {
        this.setupErrorListeners();
        this.setupPerformanceMonitoring();
        this.startQueueProcessor();
    }

    setupErrorListeners() {
        // Catch unhandled promise rejections
        window.addEventListener('unhandledrejection', (event) => {
            this.captureError({
                type: 'unhandledrejection',
                message: event.reason?.message || 'Promise rejected',
                stack: event.reason?.stack,
                timestamp: new Date().toISOString()
            });
        });

        // Catch runtime errors
        window.addEventListener('error', (event) => {
            this.captureError({
                type: 'runtime',
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                stack: event.error?.stack,
                timestamp: new Date().toISOString()
            });
        });

        // Monitor network errors
        window.addEventListener('load', () => {
            this.setupResourceErrorMonitoring();
        });
    }

    setupPerformanceMonitoring() {
        if ('performance' in window) {
            // Create PerformanceObserver for long tasks
            if ('PerformanceObserver' in window) {
                const longTaskObserver = new PerformanceObserver((list) => {
                    list.getEntries().forEach((entry) => {
                        if (entry.duration > 50) { // Tasks longer than 50ms
                            this.capturePerformanceIssue({
                                type: 'long-task',
                                duration: entry.duration,
                                startTime: entry.startTime,
                                name: entry.name
                            });
                        }
                    });
                });

                longTaskObserver.observe({ entryTypes: ['longtask'] });

                // Observe layout shifts
                const layoutShiftObserver = new PerformanceObserver((list) => {
                    list.getEntries().forEach((entry) => {
                        if (entry.value > 0.1) { // Significant layout shifts
                            this.capturePerformanceIssue({
                                type: 'layout-shift',
                                value: entry.value,
                                sources: entry.sources
                            });
                        }
                    });
                });

                layoutShiftObserver.observe({ entryTypes: ['layout-shift'] });
            }
        }
    }

    setupResourceErrorMonitoring() {
        const entries = performance.getEntriesByType('resource');
        entries.forEach(entry => {
            if (entry.transferSize === 0 && !entry.responseEnd) {
                this.captureError({
                    type: 'resource',
                    message: `Failed to load resource: ${entry.name}`,
                    timestamp: new Date().toISOString()
                });
            }
        });
    }

    getPerformanceMetrics() {
        if (!window.performance || !performance.getEntriesByType) {
            return null;
        }

        const navigation = performance.getEntriesByType('navigation')[0];
        const paint = performance.getEntriesByType('paint');
        const resources = performance.getEntriesByType('resource');

        return {
            navigation: navigation ? {
                domComplete: navigation.domComplete,
                domInteractive: navigation.domInteractive,
                loadEventEnd: navigation.loadEventEnd,
                responseEnd: navigation.responseEnd,
                responseStart: navigation.responseStart,
                domainLookupEnd: navigation.domainLookupEnd,
                domainLookupStart: navigation.domainLookupStart,
                connectEnd: navigation.connectEnd,
                connectStart: navigation.connectStart
            } : null,
            paint: paint.reduce((acc, entry) => {
                acc[entry.name] = entry.startTime;
                return acc;
            }, {}),
            resources: resources.map(resource => ({
                name: resource.name,
                duration: resource.duration,
                transferSize: resource.transferSize,
                encodedBodySize: resource.encodedBodySize,
                decodedBodySize: resource.decodedBodySize
            })),
            memory: performance.memory ? {
                usedJSHeapSize: performance.memory.usedJSHeapSize,
                totalJSHeapSize: performance.memory.totalJSHeapSize
            } : null
        };
    }

    captureError(error) {
        const errorData = {
            ...error,
            url: window.location.href,
            userAgent: navigator.userAgent,
            performance: this.getPerformanceMetrics(),
            timestamp: new Date().toISOString()
        };

        this.addToQueue(errorData);
    }

    capturePerformanceIssue(issue) {
        const performanceData = {
            ...issue,
            url: window.location.href,
            performance: this.getPerformanceMetrics(),
            timestamp: new Date().toISOString()
        };

        this.addToQueue(performanceData);
    }

    addToQueue(data) {
        this.queue.push(data);
        
        // Maintain queue size
        if (this.queue.length > this.maxQueueSize) {
            this.queue.shift();
        }

        // Flush immediately if critical error
        if (data.type === 'runtime' || data.type === 'unhandledrejection') {
            this.flushQueue();
        }
    }

    startQueueProcessor() {
        setInterval(() => this.flushQueue(), this.flushInterval);
    }

    async flushQueue() {
        if (this.queue.length === 0) return;

        try {
            const response = await fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'log_client_error',
                    nonce: window._themename?.nonce,
                    errors: this.queue
                })
            });

            if (response.ok) {
                this.queue = [];
            }
        } catch (error) {
            console.error('Failed to send error logs:', error);
        }
    }
}

export default ErrorMonitor;