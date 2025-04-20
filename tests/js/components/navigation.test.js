import { fireEvent } from '@testing-library/dom';
import '@testing-library/jest-dom';
import initNavigation from '../../../src/assets/js/components/navigation';

describe('Navigation', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <nav class="c-navigation">
                <button class="c-navigation__toggle" aria-expanded="false" aria-controls="primary-menu">
                    <span>Toggle menu</span>
                </button>
                <ul id="primary-menu">
                    <li><a href="#">Home</a></li>
                    <li class="menu-item-has-children">
                        <a href="#" aria-haspopup="true" aria-expanded="false">About</a>
                        <ul class="sub-menu">
                            <li><a href="#">Team</a></li>
                            <li><a href="#">History</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        `;
        initNavigation();
    });

    test('toggle button should update aria-expanded attribute', () => {
        const toggleButton = document.querySelector('.c-navigation__toggle');
        
        fireEvent.click(toggleButton);
        expect(toggleButton).toHaveAttribute('aria-expanded', 'true');
        
        fireEvent.click(toggleButton);
        expect(toggleButton).toHaveAttribute('aria-expanded', 'false');
    });

    test('submenu should be accessible via keyboard', () => {
        const parentLink = document.querySelector('.menu-item-has-children > a');
        const subMenu = document.querySelector('.sub-menu');
        
        fireEvent.keyDown(parentLink, { key: 'Enter' });
        expect(parentLink).toHaveAttribute('aria-expanded', 'true');
        expect(subMenu).toHaveClass('is-active');
        
        fireEvent.keyDown(parentLink, { key: 'Enter' });
        expect(parentLink).toHaveAttribute('aria-expanded', 'false');
        expect(subMenu).not.toHaveClass('is-active');
    });
});