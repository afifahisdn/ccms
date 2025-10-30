<?php
/*
* admin/admin.php
*
* This file is included at the top of almost every page in the /admin/ folder.
* It starts the session and checks if a staff member (or admin) is logged in.
* If not, it redirects them to the login page.
*/

if (session_id() == '') {
    session_start();
}

// Check for 'staff_id' (which is set for both staff and admin during login)
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit(); // Always exit after a header redirect
}

// Optional: Store role and ID in variables for easier access on the pages
$logged_in_staff_id = $_SESSION['staff_id'] ?? 0;
$logged_in_user_role = $_SESSION['user_role'] ?? 'staff'; // Default to 'staff' if not set
$logged_in_user_email = $_SESSION['admin_email'] ?? ''; // From getLogin()

?>