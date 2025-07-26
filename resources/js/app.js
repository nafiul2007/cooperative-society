import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap; // Expose Bootstrap to global window scope

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
