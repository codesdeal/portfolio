document.addEventListener('DOMContentLoaded', () => {
	// Add device detection
	const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
		navigator.userAgent
	);
	document.body.classList.toggle('is-mobile', isMobile);

	// Handle dropend menus
	const dropendMenus = document.querySelectorAll('.dropend .dropdown-toggle');

	dropendMenus.forEach((menu) => {
		menu.addEventListener('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			const parentDropdown = menu.closest('.dropend');
			const subMenu = parentDropdown.querySelector('.dropdown-menu');

			// Close other open dropdowns at the same level
			const siblings = parentDropdown.parentElement.querySelectorAll(
				'.dropend .dropdown-menu.show'
			);
			siblings.forEach((sibling) => {
				if (sibling !== subMenu) {
					sibling.classList.remove('show');
					sibling
						.closest('.dropend')
						.querySelector('.dropdown-toggle')
						.setAttribute('aria-expanded', 'false');
				}
			});

			// Toggle current dropdown
			subMenu.classList.toggle('show');
			menu.setAttribute('aria-expanded', subMenu.classList.contains('show'));
		});
	});

	// Close dropdowns when clicking outside
	document.addEventListener('click', (e) => {
		if (!e.target.closest('.dropdown')) {
			document.querySelectorAll('.dropdown-menu.show').forEach((dropdown) => {
				dropdown.classList.remove('show');
				const toggle = dropdown.closest('.dropdown, .dropend').querySelector('.dropdown-toggle');
				if (toggle) {
					toggle.setAttribute('aria-expanded', 'false');
				}
			});
		}
	});

	// Initialize mobile navigation with improved touch handling
	initMobileNavigation();

	// Smart dropdown positioning
	function checkDropdownPosition() {
		const dropdowns = document.querySelectorAll('.dropdown-menu:not(.position-checked)');

		dropdowns.forEach((dropdown) => {
			const rect = dropdown.getBoundingClientRect();
			const viewportWidth = window.innerWidth;

			if (rect.right > viewportWidth) {
				dropdown.classList.add('need-invert');
			}

			dropdown.classList.add('position-checked');
		});
	}

	// Check positions when dropdowns are shown
	document.querySelectorAll('.dropdown').forEach((dropdown) => {
		dropdown.addEventListener('mouseenter', checkDropdownPosition);
	});

	// Also check on window resize
	let resizeTimer;
	window.addEventListener('resize', () => {
		clearTimeout(resizeTimer);
		document.querySelectorAll('.dropdown-menu').forEach((menu) => {
			menu.classList.remove('position-checked', 'need-invert');
		});
		resizeTimer = setTimeout(checkDropdownPosition, 250);
	});
});

function initMobileNavigation() {
	const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
	let touchStartY = 0;
	let touchEndY = 0;

	dropdownToggles.forEach((toggle) => {
		const menuItem = toggle.closest('.menu-item-has-children');
		const link = menuItem.querySelector('a');
		let isTouch = false;

		// Handle touch start
		toggle.addEventListener(
			'touchstart',
			(e) => {
				isTouch = true;
				touchStartY = e.touches[0].clientY;
			},
			{ passive: true }
		);

		// Handle touch end
		toggle.addEventListener('touchend', (e) => {
			touchEndY = e.changedTouches[0].clientY;

			// Only handle as click if it's not a scroll attempt
			if (Math.abs(touchEndY - touchStartY) < 10) {
				e.preventDefault();
				handleToggleClick(toggle, menuItem);
			}
		});

		// Handle regular clicks
		toggle.addEventListener('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			// Only handle click if it's not triggered by touch
			if (!isTouch) {
				handleToggleClick(toggle, menuItem);
			}
			isTouch = false;
		});

		// Handle main menu link touches
		if (link) {
			link.addEventListener('touchend', (e) => {
				const subMenu = menuItem.querySelector('.dropdown-menu');
				if (subMenu && !menuItem.classList.contains('show')) {
					e.preventDefault();
				}
			});
		}
	});

	// Close dropdowns when clicking/touching outside
	document.addEventListener('click', (e) => {
		if (!e.target.closest('.menu-item-has-children')) {
			closeAllDropdowns();
		}
	});

	document.addEventListener('touchend', (e) => {
		if (!e.target.closest('.menu-item-has-children')) {
			closeAllDropdowns();
		}
	});
}

function handleToggleClick(toggle, menuItem) {
	const wasExpanded = toggle.getAttribute('aria-expanded') === 'true';
	const subMenu = menuItem.querySelector('.dropdown-menu');

	// Close other open dropdowns at the same level
	const siblingItems = menuItem.parentElement.children;
	Array.from(siblingItems).forEach((sibling) => {
		if (sibling !== menuItem && sibling.classList.contains('menu-item-has-children')) {
			const siblingToggle = sibling.querySelector('.dropdown-toggle');
			const siblingSubMenu = sibling.querySelector('.dropdown-menu');
			if (siblingToggle && siblingSubMenu) {
				siblingToggle.setAttribute('aria-expanded', 'false');
				siblingSubMenu.classList.remove('show');
				sibling.classList.remove('show');
			}
		}
	});

	// Toggle current dropdown
	toggle.setAttribute('aria-expanded', !wasExpanded);
	menuItem.classList.toggle('show');
	if (subMenu) {
		subMenu.classList.toggle('show');
	}
}

function closeAllDropdowns() {
	const openMenus = document.querySelectorAll('.menu-item-has-children.show');
	openMenus.forEach((menu) => {
		const toggle = menu.querySelector('.dropdown-toggle');
		const subMenu = menu.querySelector('.dropdown-menu');
		if (toggle && subMenu) {
			toggle.setAttribute('aria-expanded', 'false');
			subMenu.classList.remove('show');
			menu.classList.remove('show');
		}
	});
}
