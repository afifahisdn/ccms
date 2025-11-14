<?php
/*
* checkAdmin.php
*
* Ensures that only users with the 'admin' role can access the page.
* This file should be included AFTER admin.php (which starts the session).
*/

// Session is already started by admin.php, but check just in case.
if (session_id() == '') {
    session_start();
}

// Check the 'user_role' set during login
// This variable ($logged_in_user_role) is defined in admin/admin.php
if (!isset($logged_in_user_role) || $logged_in_user_role != 'admin') {
    // If not an admin, redirect them to the admin dashboard (which is safe for staff)
    header("Location: index.php");
    exit(); // Always exit after a header redirect
}
?>