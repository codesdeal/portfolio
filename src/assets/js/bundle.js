// Import dependencies
import * as bootstrap from 'bootstrap';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
import Isotope from 'isotope-layout';

// Import Swiper styles
import 'swiper/swiper-bundle.css';

import AOS from 'aos';

// Initialize Bootstrap components
document.addEventListener('DOMContentLoaded', function () {
	// for lazy load banner image
	document.querySelectorAll('.image-background').forEach((bg) => {
		const src = bg.getAttribute('data-bg');
		if (src) bg.style.backgroundImage = `url(${src})`;
	});

	// Initialize AOS
	AOS.init({
		duration: 800,
		once: true,
	});
	const offcanvasElement = document.getElementById('offcanvasNavbar');
	if (offcanvasElement) {
		const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
		const closeButton = offcanvasElement.querySelector('.btn-close');
		if (closeButton) {
			closeButton.addEventListener('click', () => {
				offcanvas.hide();
			});
		}
	}

	// Initialize Isotope when DOM is loaded and Isotope is available
	const initIsotope = () => {
		const grid = document.querySelector('.portfolio-items');
		if (grid) {
			const iso = new Isotope(grid, {
				itemSelector: '.portfolio-item',
				layoutMode: 'fitRows',
			});

			// Filter items on button click
			const filterButtons = document.querySelectorAll('.filter-buttons button');
			filterButtons.forEach((button) => {
				button.addEventListener('click', function () {
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
});
