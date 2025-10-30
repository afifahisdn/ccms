<?php
/*
* pages/assets.php
*
* Includes all common CSS and JS files for the STUDENT frontend.
* This file is included by pages/header.php.
*/

// Start session if not already started
if (session_id() == "") {
    session_start();
}
?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">

<!-- iziToast (for notifications) -->
<link rel="stylesheet" href="admin/assets/plugin/iziToast-master/dist/css/iziToast.min.css">
<script src="admin/assets/plugin/iziToast-master/dist/js/iziToast.min.js" type="text/javascript"></script>

<!-- jQuery (Main Library) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/6e8b05f9c5.js" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

<!-- SweetAlert (for confirmation dialogs) -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom JavaScript files for Student Frontend -->
<script src="admin/assets/js/include/alerts.js"></script>
<script src="admin/assets/js/include/validation.js"></script>
<script src="admin/assets/js/include/homejs.js"></script>
<script src="admin/assets/js/include/delete.js"></script>