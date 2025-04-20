import '@testing-library/jest-dom';

// Mock WordPress global objects
global.wp = {
    customize: {
        bind: jest.fn()
    }
};

// Mock jQuery
global.jQuery = jest.fn();
global.$ = global.jQuery;