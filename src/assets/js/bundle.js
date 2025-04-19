// import Slider from './components/slider.js';
import Navigation from './components/navigation.js';
import * as bootstrap from 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.min.js';
import Isotope from 'isotope-layout';

import $ from 'jquery';
import AOS from 'aos';

AOS.init({
  duration: 800,
  once: true, 
});

// Initialize Isotope when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  // Initialize Isotope
  const grid = document.querySelector('.portfolio-items');
  if (grid) {
    const iso = new Isotope(grid, {
      itemSelector: '.portfolio-item',
      layoutMode: 'fitRows'
    });

    // Filter items on button click
    const filterButtons = document.querySelectorAll('.filter-buttons button');
    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        const filterValue = this.getAttribute('data-filter');
        
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        // Add active class to current button
        this.classList.add('active');
        
        iso.arrange({ filter: filterValue === '*' ? null : filterValue });
      });
    });
  }

  // Phone validation code
  const phoneInputs = document.querySelectorAll('.us-phone');

  phoneInputs.forEach(input => {
      input.addEventListener('blur', function () {
          const phone = input.value.trim();

          // Regular expression to match US phone numbers
          const pattern = /^(\+1\s?)?(\()?(\d{3})(\))?[\s.-]?(\d{3})[\s.-]?(\d{4})$/;

          const isValid = pattern.test(phone);

          const errorMsg = input.nextElementSibling;
          if (isValid) {
              input.classList.remove('is-invalid');
              input.classList.add('is-valid');
              if (errorMsg && errorMsg.style) errorMsg.style.display = 'none';
          } else {
              input.classList.remove('is-valid');
              input.classList.add('is-invalid');
              if (errorMsg && errorMsg.style) errorMsg.style.display = 'inline';
          }
      });
  });
});
