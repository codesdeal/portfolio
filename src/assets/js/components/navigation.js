import $ from 'jquery';

$('.c-navigation').on('mouseenter', '.menu-item-has-children', (e) => {
    if(!$(e.currentTarget).parents('.sub-menu').length) {
        $('.menu > .menu-item.open').find('> a > .menu-button').trigger('click');
    }
    $(e.currentTarget).addClass('open');
}).on('mouseleave', '.menu-item-has-children', (e) => {
    $(e.currentTarget).removeClass('open');
})

$('.c-navigation').on('click', '.menu .menu-button', (e) => {
    e.preventDefault();
    e.stopPropagation();
    let menu_button = $(e.currentTarget);
    let menu_link = menu_button.parent();
    let menu_item = menu_link.parent();
    if(menu_item.hasClass('open')) {
        menu_item.add(menu_item.find('.menu-item.open')).removeClass('open');
        menu_link.add(menu_item.find('a')).attr('aria-expanded', 'false');
        menu_button.find('.menu-button-show').attr('aria-hidden', 'false');
        menu_button.find('.menu-button-hide').attr('aria-hidden', 'true');
    } else {
        menu_item.siblings('.open').find('>a>.menu-button').trigger('click');
        menu_item.addClass('open');
        menu_link.attr('aria-expanded', 'true');
        menu_button.find('.menu-button-show').attr('aria-hidden', 'true');
        menu_button.find('.menu-button-hide').attr('aria-hidden', 'false');
    }
});

$(document).click((e) => {
    if($('.menu-item.open').length) {
        $('.menu > .menu-item.open > a > .menu-button').trigger('click');
    }
})

const initNavigation = () => {
    const navToggle = document.querySelector('.c-navigation__toggle');
    const primaryMenu = document.getElementById('primary-menu');
    const subMenus = primaryMenu?.querySelectorAll('.sub-menu');
    const menuItems = primaryMenu?.querySelectorAll('li');

    // Handle menu toggle
    if (navToggle && primaryMenu) {
        navToggle.addEventListener('click', () => {
            const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', !isExpanded);
            primaryMenu.classList.toggle('is-active');
        });
    }

    // Add keyboard navigation
    menuItems?.forEach(menuItem => {
        const link = menuItem.querySelector('a');
        const subMenu = menuItem.querySelector('.sub-menu');
        
        if (subMenu) {
            // Add ARIA attributes
            link.setAttribute('aria-haspopup', 'true');
            link.setAttribute('aria-expanded', 'false');
            
            // Handle keyboard navigation
            link.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const isExpanded = link.getAttribute('aria-expanded') === 'true';
                    link.setAttribute('aria-expanded', !isExpanded);
                    subMenu.classList.toggle('is-active');
                }
            });

            // Handle mouse events
            link.addEventListener('click', (e) => {
                if (window.innerWidth > 768) {
                    e.preventDefault();
                    const isExpanded = link.getAttribute('aria-expanded') === 'true';
                    link.setAttribute('aria-expanded', !isExpanded);
                    subMenu.classList.toggle('is-active');
                }
            });
        }
    });

    // Close menus when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.c-navigation')) {
            subMenus?.forEach(subMenu => {
                subMenu.classList.remove('is-active');
                const parentLink = subMenu.parentElement.querySelector('a');
                if (parentLink) {
                    parentLink.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });

    // Handle Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            subMenus?.forEach(subMenu => {
                subMenu.classList.remove('is-active');
                const parentLink = subMenu.parentElement.querySelector('a');
                if (parentLink) {
                    parentLink.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
};

export default initNavigation;