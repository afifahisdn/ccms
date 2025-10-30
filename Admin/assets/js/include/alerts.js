/*
 * alerts.js
 *
 * Contains all JavaScript helper functions for displaying
 * iziToast notifications and SweetAlert confirmation dialogs.
 */

/**
 * Displays a success iziToast message and reloads the page upon closing.
 * @param {string} [message='Successfully processed record!'] - Optional custom message.
 * @param {string} [title='Success'] - Optional custom title.
 */
function successToast(message = 'Successfully processed record!', title = 'Success') {
    iziToast.success({
        timeout: 1200,
        title: title,
        message: message,
        position: 'center',
        onClosing: function() {
            location.reload(true); // Force reload from server
        }
    });
}

/**
 * Displays a success iziToast message for deletions and reloads the page.
 */
function successToastDelete() {
    iziToast.success({
        timeout: 1000,
        title: 'Deleted!',
        message: 'Record deleted successfully!',
        position: 'center',
        onClosing: function() {
            location.reload(true);
        }
    });
}

/**
 * Displays a success iziToast message and redirects to a specified URL upon closing.
 * @param {string} url - The URL to redirect to.
 * @param {string} [message='Successfully processed record!'] - Optional custom message.
 * @param {string} [title='Success'] - Optional custom title.
 */
function successToastRedirect(url, message = 'Successfully processed record!', title = 'Success') {
    iziToast.success({
        timeout: 1200,
        title: title,
        message: message,
        position: 'center',
        onClosing: function() {
            window.location.href = url;
        }
    });
}

/**
 * Displays a success iziToast message without reloading the page.
 * @param {string} [message='Successfully processed record!'] - Optional custom message.
 * @param {string} [title='Success'] - Optional custom title.
 */
function successToast_RN(message = 'Successfully processed record!', title = 'Success') { // RN = No Reload
    iziToast.success({
        timeout: 1500,
        title: title,
        message: message,
        position: 'center',
    });
}

/**
 * Displays an error iziToast message.
 * @param {string} message - The error message to display.
 * @param {string} [title='Error'] - Optional custom title.
 */
function errorMessage(message, title = 'Error') {
    iziToast.error({
        timeout: 3000,
        title: title,
        message: message,
        position: 'center',
    });
}

/**
 * Displays an error iziToast message and reloads the page upon closing.
 * @param {string} message - The error message to display.
 * @param {string} [title='Error'] - Optional custom title.
 */
function errorMessage_R(message, title = 'Error') { // R = Reload
    iziToast.error({
        timeout: 3000,
        title: title,
        message: message,
        position: 'center',
        onClosing: function() {
            location.reload(true);
        }
    });
}

/**
 * Displays a simple SweetAlert message without buttons, closes after a timer.
 * Does not reload the page.
 * @param {string} icon - 'success', 'error', 'warning', 'info', 'question'.
 * @param {string} title - The message title.
 */
function normalAlertNoReload(icon, title) {
    Swal.fire({
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 1500
    });
}

/**
 * Displays a simple SweetAlert message without buttons, closes after a timer,
 * and reloads the page upon closing.
 * @param {string} icon - 'success', 'error', 'warning', 'info', 'question'.
 * @param {string} title - The message title.
 */
function normalAlert(icon, title) {
    Swal.fire({
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        location.reload(true);
    });
}


/**
 * Displays a SweetAlert loading indicator with a timer. Reloads the page on close.
 * This is useful for image uploads that require a page refresh.
 * @param {string} title - The title to display while loading.
 * @param {number} [duration=1500] - Duration in milliseconds.
 */
function loading(title, duration = 1500) {
    let timerInterval;
    Swal.fire({
        title: title,
        html: 'Processing... <b></b> ms left.',
        timer: duration,
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b');
            timerInterval = setInterval(() => {
                if (b) {
                    b.textContent = Swal.getTimerLeft();
                }
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        // Reload after the timer is done
        location.reload(true);
    });
}


/**
 * Displays a success iziToast and forces logout for Admin actions (e.g., password change).
 * @param {string} [message='Action successful! Please log in again.'] - Optional message.
 */
function successToastwithLogoutInAdmin(message = 'Action successful! Please log in again.') {
    iziToast.success({
        timeout: 1500,
        title: 'Success',
        message: message,
        position: 'center',
        onClosing: function() {
            window.location.href = 'logout.php'; // Redirect to admin logout
        }
    });
}

/**
 * Displays a success iziToast and forces logout for Student actions (e.g., password/email change).
 * @param {string} [message='Action successful! Please log in again.'] - Optional message.
 */
function successToastwithLogout(message = 'Action successful! Please log in again.') {
    iziToast.success({
        timeout: 1500,
        title: 'Success',
        message: message,
        position: 'center',
        onClosing: function() {
            // Redirects to the student logout script in the root folder
            window.location.href = 'logout.php'; 
        }
    });
}