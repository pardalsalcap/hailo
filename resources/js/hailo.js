import {launchConfirmModal, launchToast} from './alerts';


window.addEventListener('toast-success', event => {
    launchToast(event.detail[0].title);
});

window.addEventListener('toast-error', event => {
    launchToast(event.detail[0].title, 'error');
});

window.addEventListener('confirm-modal-destroy', event => {
    console.log(event.detail);
    launchConfirmModal(event.detail[0].title, event.detail[0].text, event.detail[0].confirmButtonText, event.detail[0].cancelButtonText, event.detail[0].livewireAction, event.detail[0].id)
    //title, text, confirmButtonText, cancelButtonText, livewireAction, id)
});
