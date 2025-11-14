<head>
    <?php
    /*
     * admin/pages/header.php
     *
     * Includes all core function files, assets, and sets page defaults.
     */

    // --- CRITICAL: INCLUDE ALL FUNCTION FILES ---
    // We include the function libraries directly, NOT api.php
    // __DIR__ is '.../admin/pages'
    // We need to go up two levels to '.../' then to 'server/inc/'
    include_once __DIR__ . '/../../server/inc/connection.php';
    include_once __DIR__ . '/../../server/inc/get.php';
    include_once __DIR__ . '/../../server/inc/add.php';
    include_once __DIR__ . '/../../server/inc/update.php';
    include_once __DIR__ . '/../../server/inc/delete.php';

    // Include asset list (loads all JS/CSS links)
    include_once 'pages/assets.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCMS Admin Panel</title>

    <!-- CSS Includes (loaded from assets.php, but also listed here for clarity) -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/iconly/bold.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">

    <!-- Simple Datatables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />

    <!-- iziToast CSS -->
    <link rel="stylesheet" href="assets/plugin/iziToast-master/dist/css/iziToast.min.css">

    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>