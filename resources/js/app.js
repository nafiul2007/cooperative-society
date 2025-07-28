
import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap; // Expose Bootstrap to global window scope

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


window.loadOverlayLogo = function (status) {

    if('on' == status) $('#loaderOverlay').fadeIn();
    else $('#loaderOverlay').fadeOut();

    // $('#loaderOverlay').fadeIn();
    // setTimeout(() => {
    //     $('#loaderOverlay').fadeOut();
    // }, 3000);
};


// Bootstrap 5 form validation
(function () {
    'use strict';

    window.addEventListener('load', function () {
        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    });
})();
