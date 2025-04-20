import { fireEvent } from '@testing-library/dom';
import '@testing-library/jest-dom';
import KeyboardNavigation from '../../../src/assets/js/modules/keyboard-navigation';

describe('KeyboardNavigation', () => {
    let keyboardNav;
    
    beforeEach(() => {
        document.body.innerHTML = `
            <nav>
                <ul>
                    <li class="menu-item-has-children">
                        <a href="#">Parent Menu</a>
                        <ul class="sub-menu">
                            <li><a href="#">Child 1</a></li>
                            <li><a href="#">Child 2</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="c-modal">
                    <button class="c-modal__close">Close</button>
                    <a href="#" class="modal-link">Link</a>
                    <button class="modal-button">Button</button>
                </div>
            </nav>
        `;
        keyboardNav = new KeyboardNavigation();
    });

    test('improves dropdown accessibility with ARIA attributes', () => {
        const parentLink = document.querySelector('.menu-item-has-children > a');
        const submenu = document.querySelector('.sub-menu');
        
        expect(parentLink).toHaveAttribute('aria-expanded', 'false');
        expect(parentLink).toHaveAttribute('aria-haspopup', 'true');
        expect(parentLink).toHaveAttribute('aria-controls');
        expect(submenu).toHaveAttribute('role', 'menu');
    });

    test('handles arrow key navigation in menus', () => {
        const parentLink = document.querySelector('.menu-item-has-children > a');
        const firstChild = document.querySelector('.sub-menu a');
        
        fireEvent.keyDown(parentLink, { key: 'ArrowDown' });
        expect(document.activeElement).toBe(firstChild);
    });

    test('handles escape key to close menus', () => {
        const parentLink = document.querySelector('.menu-item-has-children > a');
        parentLink.setAttribute('aria-expanded', 'true');
        
        fireEvent.keyDown(document, { key: 'Escape' });
        expect(parentLink).toHaveAttribute('aria-expanded', 'false');
    });

    test('traps focus within modal', () => {
        const modal = document.querySelector('.c-modal');
        const closeButton = modal.querySelector('.c-modal__close');
        const lastButton = modal.querySelector('.modal-button');
        
        modal.classList.add('is-active');
        
        // Test forward tabbing
        document.activeElement = lastButton;
        fireEvent.keyDown(document, { key: 'Tab' });
        expect(document.activeElement).toBe(closeButton);
        
        // Test backward tabbing
        document.activeElement = closeButton;
        fireEvent.keyDown(document, { key: 'Tab', shiftKey: true });
        expect(document.activeElement).toBe(lastButton);
    });
});