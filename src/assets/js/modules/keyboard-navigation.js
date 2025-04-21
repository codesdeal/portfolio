class KeyboardNavigation {
	constructor(rootElement) {
		this.rootElement = rootElement;
		this.doc = rootElement.ownerDocument;
		this.focusableElementsString =
			'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), [tabindex="0"]';
		this.lastFocusedElement = null;
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
		this.doc.addEventListener('keydown', (event) => {
			if (event.key === 'Tab') {
				const modal = this.doc.querySelector('.c-modal.is-active');
				if (modal) {
					this.handleModalTabbing(event, modal);
				}
			}
		});

		// Handle arrow key navigation in menus
		this.rootElement.querySelectorAll('.menu-item-has-children > a').forEach((item) => {
			item.addEventListener('keydown', (event) => {
				this.handleMenuKeyboardNavigation(event, item);
			});
		});

		// Focus management for modals
		this.rootElement.querySelectorAll('.c-modal').forEach((modal) => {
			modal.addEventListener('show.modal', () => {
				this.trapFocus(modal);
				this.storeLastFocus(modal);
			});

			modal.addEventListener('hide.modal', () => {
				this.restoreLastFocus();
			});
		});
	}

	improveDropdownAccessibility() {
		this.rootElement.querySelectorAll('.menu-item-has-children').forEach((item) => {
			const link = item.querySelector('a');
			const submenu = item.querySelector('.sub-menu');
			const submenuId = `submenu-${Math.random().toString(36).substr(2, 9)}`;

			link.setAttribute('aria-expanded', 'false');
			link.setAttribute('aria-haspopup', 'true');
			link.setAttribute('aria-controls', submenuId);

			if (submenu) {
				submenu.id = submenuId;
				submenu.setAttribute('role', 'menu');
				submenu.querySelectorAll('a').forEach((subLink) => {
					subLink.setAttribute('role', 'menuitem');
				});
			}

			link.addEventListener('keydown', (event) => {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					this.toggleSubmenu(item);
				}
			});

			link.addEventListener('click', (event) => {
				event.preventDefault();
				this.toggleSubmenu(item);
			});
		});
	}

	setupFocusTrap() {
		this.doc.addEventListener('keydown', (event) => {
			if (event.key === 'Tab') {
				const modal = this.rootElement.querySelector('.c-modal.is-active');
				if (modal) {
					this.handleModalTabbing(event, modal);
				}
			}
		});
	}

	handleEscapeKey() {
		this.doc.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				const modal = this.rootElement.querySelector('.c-modal.is-active');
				if (modal) {
					modal.dispatchEvent(new CustomEvent('hide.modal'));
				}

				this.rootElement.querySelectorAll('.menu-item-has-children.is-active').forEach((item) => {
					this.toggleSubmenu(item, false);
				});
			}
		});
	}

	handleModalTabbing(event, modal) {
		const focusableElements = modal.querySelectorAll(this.focusableElementsString);
		const firstFocusable = focusableElements[0];
		const lastFocusable = focusableElements[focusableElements.length - 1];
		const activeElement = modal.ownerDocument.activeElement;

		if (event.shiftKey) {
			if (activeElement === firstFocusable) {
				event.preventDefault();
				lastFocusable.focus();
			}
		} else if (activeElement === lastFocusable) {
			event.preventDefault();
			firstFocusable.focus();
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
		const skipLink = this.rootElement.querySelector('.u-skip-link');
		if (skipLink) {
			skipLink.addEventListener('click', (event) => {
				event.preventDefault();
				const target = this.rootElement.querySelector(skipLink.getAttribute('href'));
				if (target) {
					target.tabIndex = -1;
					target.focus();
				}
			});
		}
	}

	storeLastFocus(element) {
		this.lastFocusedElement = element.ownerDocument.activeElement;
	}

	restoreLastFocus() {
		if (this.lastFocusedElement) {
			this.lastFocusedElement.focus();
		}
	}
}

export default KeyboardNavigation;
