import * as bootstrap from 'bootstrap';
import KeyboardNavigation from '../modules/keyboard-navigation';

const initNavigation = () => {
	const navbar = document.querySelector('.navbar');
	if (!navbar) return;

	const navbarToggler = navbar.querySelector('.navbar-toggler');
	const navbarCollapse = navbar.querySelector('.navbar-collapse');

	if (!navbarToggler || !navbarCollapse) return;

	// Initialize keyboard navigation with navbar as root element
	new KeyboardNavigation(navbar);

	const handleResize = () => {
		const isMobile = window.innerWidth < 992;
		document.body.classList.toggle('is-mobile-nav', isMobile);

		// Reset mobile menu state when switching to desktop
		if (!isMobile) {
			document.querySelectorAll('.menu-item-has-children.show').forEach((item) => {
				item.classList.remove('show');
				item.querySelector('.dropdown-toggle')?.setAttribute('aria-expanded', 'false');
			});
		}

		if (window.innerWidth >= 992) {
			navbarCollapse.classList.remove('show');
		}
	};

	// Initialize all dropdown menus
	document.querySelectorAll('.menu-item-has-children').forEach((item) => {
		const link = item.querySelector('a');
		const dropdownToggle = item.querySelector('.dropdown-toggle');
		const subMenu = item.querySelector('.sub-menu');

		if (dropdownToggle && subMenu) {
			// Handle dropdown toggle click
			dropdownToggle.addEventListener('click', (e) => {
				e.preventDefault();
				e.stopPropagation();

				const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true';

				if (document.body.classList.contains('is-mobile-nav')) {
					// Close other dropdowns at the same level
					const parentUl = item.parentElement;
					parentUl.querySelectorAll(':scope > .menu-item-has-children.show').forEach((openItem) => {
						if (openItem !== item) {
							openItem.classList.remove('show');
							openItem.querySelector('.dropdown-toggle')?.setAttribute('aria-expanded', 'false');
						}
					});
				}

				// Toggle current dropdown
				item.classList.toggle('show');
				dropdownToggle.setAttribute('aria-expanded', !isExpanded);
			});

			// Handle keyboard navigation
			link.addEventListener('keydown', (event) => {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					dropdownToggle.click();
				}
			});

			// Stop click propagation on submenu items
			subMenu.addEventListener('click', (e) => {
				e.stopPropagation();
			});
		}
	});

	const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
		toggle: false,
	});

	navbarToggler.addEventListener('click', () => {
		bsCollapse.toggle();
	});

	// Close mobile menu when clicking outside
	document.addEventListener('click', (event) => {
		const isNavClick = event.target.closest('.navbar');
		if (!isNavClick && navbarCollapse.classList.contains('show')) {
			bsCollapse.hide();
		}
	});

	// Initialize resize handler
	handleResize();
	window.addEventListener('resize', handleResize);
};

export default initNavigation;
