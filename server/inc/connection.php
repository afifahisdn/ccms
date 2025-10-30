<?php
/*
* connection.php
* Establishes the database connection.
*/

// Build connection between database and system
$con = mysqli_connect("localhost", "root", "", "ccms");

// testing if connection succesfully
if (!$con) {
    // Use error_log for production environments instead of die()
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Connection failed. Please try again later.");
}

// Set charset to utf8mb4 for full unicode support
mysqli_set_charset($con, "utf8mb4");

?>

