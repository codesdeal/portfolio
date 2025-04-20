// Import dependencies
import * as bootstrap from 'bootstrap';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import AOS from 'aos';

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap dropdowns and collapse
    const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
    const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
    
    const collapseElementList = document.querySelectorAll('.navbar-toggler');
    const collapseList = [...collapseElementList].map(collapseEl => new bootstrap.Collapse(collapseEl.dataset.bsTarget, {
        toggle: false
    }));

    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
    });

    // Initialize Isotope when DOM is loaded and Isotope is available
    const initIsotope = () => {
        const grid = document.querySelector('.portfolio-items');
        if (grid && typeof Isotope !== 'undefined') {
            const iso = new Isotope(grid, {
                itemSelector: '.portfolio-item',
                layoutMode: 'fitRows',
            });

            // Filter items on button click
            const filterButtons = document.querySelectorAll('.filter-buttons button');
            filterButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    const filterValue = this.getAttribute('data-filter');
                    
                    // Remove active class from all buttons
                    filterButtons.forEach((btn) => btn.classList.remove('active'));
                    // Add active class to current button
                    this.classList.add('active');
                    
                    iso.arrange({
                        filter: filterValue === '*' ? null : filterValue,
                    });
                });
            });
        }
    };

    // Try to initialize immediately if Isotope is already loaded
    if (typeof Isotope !== 'undefined') {
        initIsotope();
    } else {
        // If Isotope isn't loaded yet, wait for it
        const checkIsotope = setInterval(() => {
            if (typeof Isotope !== 'undefined') {
                initIsotope();
                clearInterval(checkIsotope);
            }
        }, 100);
        // Stop checking after 5 seconds to prevent infinite loop
        setTimeout(() => clearInterval(checkIsotope), 5000);
    }

    // Initialize Testimonials Slider
    const testimonialSlider = document.querySelector('.testimonials-slider');
    if (testimonialSlider) {
        new Swiper('.testimonials-slider', {
            modules: [Navigation, Pagination, Autoplay],
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                992: {
                    slidesPerView: 3,
                },
            },
        });
    }

    // Phone validation code
    const phoneInputs = document.querySelectorAll('.us-phone');
    phoneInputs.forEach((input) => {
        input.addEventListener('blur', function() {
            const phone = input.value.trim();
            const pattern = /^(\+1\s?)?(\()?(\d{3})(\))?[\s.-]?(\d{3})[\s.-]?(\d{4})$/;
            const isValid = pattern.test(phone);
            const errorMsg = input.nextElementSibling;

            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                if (errorMsg && errorMsg.style) {
                    errorMsg.style.display = 'none';
                }
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                if (errorMsg && errorMsg.style) {
                    errorMsg.style.display = 'inline';
                }
            }
        });
    });
});
