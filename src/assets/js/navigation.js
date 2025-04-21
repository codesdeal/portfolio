document.addEventListener('DOMContentLoaded', () => {
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

	// Initialize mobile navigation
	initMobileNavigation();
});

function initMobileNavigation() {
	const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

	dropdownToggles.forEach((toggle) => {
		toggle.addEventListener('click', (e) => {
			e.preventDefault();
			e.stopPropagation();

			const menuItem = toggle.closest('.menu-item-has-children');
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
		});
	});

	// Add touch event handling for better mobile experience
	const menuItems = document.querySelectorAll('.menu-item-has-children');
	menuItems.forEach((item) => {
		const link = item.querySelector('a');
		if (link) {
			link.addEventListener('touchend', (e) => {
				if (!item.classList.contains('show')) {
					e.preventDefault();
				}
			});
		}
	});
}
