import * as bootstrap from 'bootstrap';

const initNavigation = () => {
	const navbar = document.querySelector('.navbar');
	if (!navbar) return;

	const navbarToggler = navbar.querySelector('.navbar-toggler');
	const navbarCollapse = navbar.querySelector('.navbar-collapse');

	if (!navbarToggler || !navbarCollapse) return;

	const handleResize = () => {
		const isMobile = window.innerWidth < 992;
		document.body.classList.toggle('is-mobile-nav', isMobile);
	};

	// Handle mobile menu toggles
	document.querySelectorAll('.menu-item-has-children').forEach((item) => {
		const link = item.querySelector('a');
		const subMenu = item.querySelector('.dropdown-menu');

		if (link && subMenu) {
			// Only handle clicks on mobile
			link.addEventListener('click', (e) => {
				if (!document.body.classList.contains('is-mobile-nav')) return;

				e.preventDefault();
				e.stopPropagation();

				// Close other dropdowns at the same level
				const parentUl = item.parentElement;
				parentUl.querySelectorAll(':scope > .menu-item-has-children.show').forEach((openItem) => {
					if (openItem !== item) {
						openItem.classList.remove('show');
						const openLink = openItem.querySelector('a');
						if (openLink) openLink.setAttribute('aria-expanded', 'false');
					}
				});

				// Toggle current dropdown
				const isExpanded = link.getAttribute('aria-expanded') === 'true';
				item.classList.toggle('show');
				link.setAttribute('aria-expanded', (!isExpanded).toString());
			});
		}
	});

	// Handle mobile menu toggle
	const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
		toggle: false,
	});

	navbarToggler.addEventListener('click', () => {
		bsCollapse.toggle();
	});

	// Close mobile menu when clicking outside
	document.addEventListener('click', (event) => {
		if (!event.target.closest('.navbar') && navbarCollapse.classList.contains('show')) {
			bsCollapse.hide();
		}
	});

	// Initialize resize handler
	handleResize();
	window.addEventListener('resize', handleResize);
};

export default initNavigation;
