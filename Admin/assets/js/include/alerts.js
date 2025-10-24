function successToast() {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully inserted record!',
        position: 'center',
        onClosing: function () {
           location.reload(true);
        }
    })
}

function successToastDelete() {
    iziToast.success({
        timeout: 500,
        title: 'Deleting..',
        message: 'Successfully deleted record!',
        position: 'center',
        onClosing: function () {
            location.reload(true);
        }
    })
}

function successToastRedirect(url) {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully inserted record!',
        position: 'center',
        onClosing: function () {
           window.location.href = url;
        }
    })
}
function successToast() {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully inserted record!',
        position: 'center',
        onClosing: function () {
            location.reload(true);
        }
    })
}

function successToast_RN() {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully inserted record!',
        position: 'center',
    })
}

function errorMessage(title) {
    iziToast.error({
        timeout: 2000,
        title: 'Error',
        message: title,
        position: 'center',
    });
}

function errorMessage_R(title) {
    iziToast.error({
        timeout: 2000,
        title: 'Error',
        message: title,
        position: 'center',
        onClosing: function () {
            location.reload(true);
        }
    });
}

function normalAlertNoReload(icon, title) {
    Swal.
        fire({
            icon: icon,
            title: title,
            showConfirmButton: false, timer: 1500
        });
}

function normalAlert(icon, title) {
    Swal.
        fire({
            icon: icon,
            title: title,
            showConfirmButton: false, timer: 1500
        }).then(() => {
            location.reload(true);
        });
}


function loading(gettitle) {
    let timerInterval
    Swal.fire({
        title: gettitle,
        html: 'Loading.. <b></b> Ms.',
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading()
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
                b.textContent = Swal.getTimerLeft()
            }, 100)
        },
        willClose: () => {
            clearInterval(timerInterval)
        }
    }).then((result) => {
        /* Read more about handling dismissals below */
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log('Loading');
            successToast();
            location.reload(true);
        }
    })
}


function successToastwithLogoutInAdmin() {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully Changed!',
        position: 'center',
        onClosing: function () {
            window.location.href = 'logout.php';
        }
    })
}

function successToastwithLogout() {
    iziToast.success({
        timeout: 1000,
        title: 'Saving..',
        message: 'Successfully Changed!',
        position: 'center',
        onClosing: function () {
            window.location.href = 'admin/logout.php';
        }
    })
}