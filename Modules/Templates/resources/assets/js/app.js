// import './bootstrap';
import 'flowbite';
import './index';
// import Alpine from 'alpinejs';
import '../../../../../vendor/power-components/livewire-powergrid/dist/powergrid';
import '../../../../../vendor/power-components/livewire-powergrid/dist/tailwind.css';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

// window.Alpine = Alpine;

// Alpine.start();
window.onload = function () {
    document.querySelector('.loading').style.display = 'none';
};

// Import the main React/Inertia setup
import './app.jsx';