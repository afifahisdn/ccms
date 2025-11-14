<?php
/*
* admin/pages/assets.php
*
* Includes all common CSS and JS files for the ADMIN panel.
* This file is included by admin/pages/header.php.
*/

// Start session if not already started
if (session_id() == "") {
    session_start();
}
?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.css">

<!-- Admin Panel Icons -->
<link rel="stylesheet" href="assets/vendors/iconly/bold.css">
<link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">

<!-- Scrollbar -->
<link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">

<!-- Main Admin Panel CSS -->
<link rel="stylesheet" href="assets/css/app.css">

<!-- Simple Datatables CSS (for tables) -->
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />

<!-- iziToast (for notifications) -->
<link rel="stylesheet" href="assets/plugin/iziToast-master/dist/css/iziToast.min.css">
<script src="assets/plugin/iziToast-master/dist/js/iziToast.min.js" type="text/javascript"></script>

<!-- SweetAlert (for confirmation dialogs) -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/6e8b05f9c5.js" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

<!-- jQuery (Main Library) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Simple Datatables JS -->
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>

<!-- === Custom JavaScript files for Admin Panel === -->
<script src="assets/js/include/alerts.js"></script>
<script src="assets/js/include/validation.js"></script>
<script src="assets/js/include/main.js"></script>
<script src="assets/js/include/add.js"></script>
<script src="assets/js/include/delete.js"></script>
<script src="assets/js/include/upload.js"></script>