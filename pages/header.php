<?php
/*
* pages/header.php
*
* Includes session_start (via assets.php), connects to DB (via connection.php),
* fetches all settings (via get.php), and includes all CSS/JS assets (via assets.php).
* This file provides the $res variable (containing settings) to all pages that include it.
*/

// Start session if not already started (assets.php might also do this)
if (session_id() == "") {
    session_start();
}

// --- Corrected Includes ---
// Include only the necessary files directly
// Use __DIR__ for reliable pathing from an included file
include_once __DIR__ . "/../server/inc/connection.php"; // Establishes $con
include_once __DIR__ . "/../server/inc/get.php";        // Provides getAllSettings()
include_once __DIR__ . "/assets.php";                // Includes CSS/JS links

// Fetch settings from the database with error handling
$setting_result = getAllSettings(); // This function is from get.php
$res = null; // Initialize $res

if ($setting_result) {
    $res = mysqli_fetch_assoc($setting_result);
    if (!$res) {
        // Log error, provide defaults
        error_log("Error fetching settings row from database.");
    }
} else {
    error_log("Error executing getAllSettings query: " . (isset($con) ? mysqli_error($con) : "DB connection failed")); // Log DB error
}

// --- Provide Default Fallbacks ---
// If $res is null or failed, use defaults to prevent errors on the page
if (!$res) {
    $res = [
        "header_image" => "logo.png", // Provide a default logo name
        "sub_image" => "default_sub.jpg",
        "about_image" => "default_about.jpg",
        "background_image" => "default_background.jpg",
        "header_title" => "College Complaint Management System",
        "header_desc" => "Welcome! Please log in or submit a complaint.",
        "about_title" => "About Our System",
        "about_desc" => "This system allows students to submit and track campus complaints efficiently.",
        "company_phone" => "N/A",
        "company_email" => "support@college.edu",
        "company_address" => "123 College Way, 40000 Shah Alam, Selangor",
        "link_facebook" => "#",
        "link_twitter" => "#",
        "link_instagram" => "#",
    ];
}

// --- Prepare Image Sources (using defaults if $res failed) ---
// Use htmlspecialchars to prevent XSS
$header_image_filename = htmlspecialchars($res["header_image"] ?? 'logo.png');
$header_src = "server/uploads/settings/" . basename($header_image_filename); // Use basename for security

$subheader_filename = htmlspecialchars($res["sub_image"] ?? 'default_sub.jpg');
$subheader_src = "server/uploads/settings/" . basename($subheader_filename);

$about_image_filename = htmlspecialchars($res["about_image"] ?? 'default_about.jpg');
$about_src = "server/uploads/settings/" . basename($about_image_filename);

$background_image_filename = htmlspecialchars($res["background_image"] ?? 'default_background.jpg');
$background_image_src = "server/uploads/settings/" . basename($background_image_filename);
?>

<head>
    <title>College Complaint Management System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Stylesheets (included via assets.php) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,700,900|Playfair+Display:200,300,400,700">
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/aos.css">
    <link rel="stylesheet" href="css/style.css">
</head>