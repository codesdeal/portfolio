
// import Slider from './components/slider.js';
import Navigation from './components/navigation.js';
import * as bootstrap from 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.min.js';

import $ from 'jquery';
import AOS from 'aos';

AOS.init({
  duration: 800,
  once: true, 
});

document.addEventListener('DOMContentLoaded', function () {
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
