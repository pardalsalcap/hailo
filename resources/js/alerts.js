import Swal from 'sweetalert2';
export function launchToast (title, type){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2400,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        showClass: {
            popup: 'animate__animated animate__fadeIn'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut'
        }
    });
    if (type == 'error')
    {
        Toast.fire({
            title: title,
            background: 'rgb(239, 68, 68)',
            customClass: {
                popup: 'toast-error',
            }
        });
    }
    else
    {
        Toast.fire({
            title: title,
            background: '#88C100',
            customClass: {
                popup: 'toast-success'
            }
        });
    }
}

export function launchConfirmModal (title, text, confirmButtonText, cancelButtonText, livewireAction, id){
    Swal.fire({
        title: title,
        text: text,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        showCancelButton: true,
        confirmButtonColor: '#e5291d',
        cancelButtonColor: '#84cc16',
        showClass: {
            popup: 'animate__animated animate__fadeIn'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut'
        },
        customClass: {
            title: 'swal-confirm-title',
            content: 'swal-confirm-content',
            header: 'swal-confirm-header',
            actions: 'swal-confirm-actions',
            confirm: 'swal-confirm-confirm'
        }
    }).then((result) => {
        console.log(livewireAction);
        if (result.isConfirmed && livewireAction !== undefined) {
            Livewire.dispatch(livewireAction, {id: id})
        }
    });
}
