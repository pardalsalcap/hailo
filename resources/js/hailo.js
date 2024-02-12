import {launchConfirmModal, launchToast} from './alerts';


window.addEventListener('toast-success', event => {
    launchToast(event.detail[0].title);
});

window.addEventListener('toast-error', event => {
    console.log(event.detail);
    launchToast(event.detail[0].title, 'error');
});

window.addEventListener('confirm-modal-destroy', event => {
    console.log(event.detail);
    launchConfirmModal(event.detail[0].title, event.detail[0].text, event.detail[0].confirmButtonText, event.detail[0].cancelButtonText, event.detail[0].livewireAction, event.detail[0].id)
    //title, text, confirmButtonText, cancelButtonText, livewireAction, id)
});

export function humanFileSize(bytes, si=false, dp=1) {
    const thresh = si ? 1000 : 1024;

    if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
    }

    const units = si
        ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
        : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    let u = -1;
    const r = 10**dp;

    do {
        bytes /= thresh;
        ++u;
    } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


    return bytes.toFixed(dp) + ' ' + units[u];
}
window.humanFileSize = humanFileSize;
