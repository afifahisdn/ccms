<?php
/*
* admin/logout.php
*
* Destroys the admin/staff session and redirects to the admin login page.
*/

// Start the session to access it
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect to the admin login page
header("Location: login.php");
exit; // Ensure no further code is executed
?>