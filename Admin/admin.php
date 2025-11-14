<?php
/*
* admin/admin.php
*
* This file is included at the top of almost every page in the /admin/ folder.
* It starts the session and checks if a staff member (or admin) is logged in.
* If not, it redirects them to the login page.
* It also defines global variables for the logged-in user.
*/

if (session_id() == '') {
    session_start();
}

// Check for 'staff_id' (which is set for both staff and admin during login)
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit(); // Always exit after a header redirect
}

// Set global variables for all admin pages to use
$logged_in_staff_id = $_SESSION['staff_id'] ?? 0;
$logged_in_user_role = $_SESSION['user_role'] ?? 'staff'; // Default to 'staff' if not set
$logged_in_user_email = $_SESSION['admin_email'] ?? ''; // From getLogin()

?>