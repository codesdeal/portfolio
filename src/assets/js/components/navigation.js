import * as bootstrap from 'bootstrap';

const initNavigation = () => {
    // Initialize dropdowns for menu items with children
    document.querySelectorAll('.menu-item-has-children > a').forEach(item => {
        const dropdownToggle = document.createElement('span');
        dropdownToggle.className = 'dropdown-toggle ms-1';
        dropdownToggle.setAttribute('data-bs-toggle', 'dropdown');
        dropdownToggle.setAttribute('aria-expanded', 'false');
        item.appendChild(dropdownToggle);
        
        const subMenu = item.nextElementSibling;
        if (subMenu && subMenu.classList.contains('sub-menu')) {
            subMenu.classList.add('dropdown-menu');
            item.parentElement.classList.add('dropdown');
            new bootstrap.Dropdown(dropdownToggle);
        }
    });

    // Convert menu items to Bootstrap nav items
    document.querySelectorAll('#primary-menu li').forEach(item => {
        item.classList.add('nav-item');
        const link = item.querySelector('a');
        if (link) {
            link.classList.add('nav-link');
        }
    });

    // Handle mobile menu toggle
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    if (navbarToggler && navbarCollapse) {
        new bootstrap.Collapse(navbarCollapse, {
            toggle: false
        });
    }
};

export default initNavigation;