import './bootstrap';
import 'flowbite';

window.openModal = function(id) {
    document.getElementById(id).classList.remove('hidden');
}

window.closeModal = function(id) {
    document.getElementById(id).classList.add('hidden');
}
