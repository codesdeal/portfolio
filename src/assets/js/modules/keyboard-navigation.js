class KeyboardNavigation {
    constructor() {
        this.focusableElementsString = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex="0"]';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.improveDropdownAccessibility();
        this.setupFocusTrap();
        this.handleEscapeKey();
        this.setupSkipLinks();
    }

    setupEventListeners() {
        // Handle tab navigation within modal windows
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Tab') {
                const modal = document.querySelector('.c-modal.is-active');
                if (modal) {
                    this.handleModalTabbing(event, modal);
                }
            }
        });

        // Handle arrow key navigation in menus
        document.querySelectorAll('.menu-item-has-children > a').forEach(item => {
            item.addEventListener('keydown', (event) => {
                this.handleMenuKeyboardNavigation(event, item);
            });
        });

        // Focus management for modals
        document.querySelectorAll('.c-modal').forEach(modal => {
            modal.addEventListener('show.modal', () => {
                this.trapFocus(modal);
                this.storeLastFocus();
            });

            modal.addEventListener('hide.modal', () => {
                this.restoreLastFocus();
            });
        });
    }

    improveDropdownAccessibility() {
        document.querySelectorAll('.menu-item-has-children').forEach(item => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('.sub-menu');
            const submenuId = `submenu-${Math.random().toString(36).substr(2, 9)}`;

            // Set ARIA attributes
            link.setAttribute('aria-expanded', 'false');
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-controls', submenuId);
            
            if (submenu) {
                submenu.id = submenuId;
                submenu.setAttribute('role', 'menu');
                submenu.querySelectorAll('a').forEach(subLink => {
                    subLink.setAttribute('role', 'menuitem');
                });
            }

            // Toggle submenu on Enter/Space
            link.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.toggleSubmenu(item);
                }
            });
        });
    }

    setupFocusTrap() {
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Tab') {
                const modal = document.querySelector('.c-modal.is-active');
                if (modal) {
                    this.handleModalTabbing(event, modal);
                }
            }
        });
    }

    handleEscapeKey() {
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                // Close active modal
                const modal = document.querySelector('.c-modal.is-active');
                if (modal) {
                    modal.dispatchEvent(new CustomEvent('hide.modal'));
                }

                // Close active dropdowns
                document.querySelectorAll('.menu-item-has-children.is-active').forEach(item => {
                    this.toggleSubmenu(item, false);
                });
            }
        });
    }

    handleModalTabbing(event, modal) {
        const focusableElements = modal.querySelectorAll(this.focusableElementsString);
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        if (event.shiftKey) {
            if (document.activeElement === firstFocusable) {
                event.preventDefault();
                lastFocusable.focus();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                event.preventDefault();
                firstFocusable.focus();
            }
        }
    }

    handleMenuKeyboardNavigation(event, item) {
        const submenu = item.parentElement.querySelector('.sub-menu');
        if (!submenu) return;

        switch (event.key) {
            case 'ArrowDown':
                event.preventDefault();
                if (item.getAttribute('aria-expanded') === 'false') {
                    this.toggleSubmenu(item.parentElement, true);
                }
                submenu.querySelector('a').focus();
                break;
            case 'ArrowUp':
                event.preventDefault();
                if (item.getAttribute('aria-expanded') === 'true') {
                    this.toggleSubmenu(item.parentElement, false);
                }
                break;
            case 'Escape':
                event.preventDefault();
                this.toggleSubmenu(item.parentElement, false);
                item.focus();
                break;
        }
    }

    toggleSubmenu(menuItem, force) {
        const link = menuItem.querySelector('a');
        const submenu = menuItem.querySelector('.sub-menu');
        const isOpen = force !== undefined ? force : link.getAttribute('aria-expanded') === 'false';

        link.setAttribute('aria-expanded', isOpen);
        menuItem.classList.toggle('is-active', isOpen);
        
        if (isOpen) {
            submenu.style.display = 'block';
            this.setupSubmenuKeyboardNav(submenu);
        } else {
            submenu.style.display = 'none';
        }
    }

    setupSubmenuKeyboardNav(submenu) {
        const items = submenu.querySelectorAll('a');
        items.forEach((item, index) => {
            item.addEventListener('keydown', (event) => {
                switch (event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        if (index < items.length - 1) {
                            items[index + 1].focus();
                        }
                        break;
                    case 'ArrowUp':
                        event.preventDefault();
                        if (index > 0) {
                            items[index - 1].focus();
                        } else {
                            submenu.parentElement.querySelector('a').focus();
                        }
                        break;
                }
            });
        });
    }

    setupSkipLinks() {
        const skipLink = document.querySelector('.u-skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', (event) => {
                event.preventDefault();
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.tabIndex = -1;
                    target.focus();
                }
            });
        }
    }

    storeLastFocus() {
        this.lastFocusedElement = document.activeElement;
    }

    restoreLastFocus() {
        if (this.lastFocusedElement) {
            this.lastFocusedElement.focus();
        }
    }
}

export default KeyboardNavigation;