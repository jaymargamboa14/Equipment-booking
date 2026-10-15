import './bootstrap';
import { initBookingForm } from './booking-form';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', initBookingForm);
