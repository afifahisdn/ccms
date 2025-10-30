<?php
/*
* auth.php
* Checks if a student is logged in.
* This file is included on all pages that require a student session.
* It also fetches the logged-in student's data into $student_data.
*/

// Start session if it hasn't been started already
if (session_id() == '') {
    session_start();
}

// Redirect to login if student_id is not set in the session
if (!isset($_SESSION['student_id'])) {
    // Redirect to the main login page
    header("Location: login.php");
    exit(); // Important: stop script execution after redirect
} else {
    // Include necessary files ONLY if session exists
    // These provides $con and the getStudentById() function
    include_once "server/inc/connection.php";
    include_once "server/inc/get.php";

    // Fetch student data using the student_id from the session
    $getall = getStudentById($_SESSION['student_id']);

    // Check if the query was successful and returned a valid student
    if ($getall && mysqli_num_rows($getall) > 0) {
        $student_data = mysqli_fetch_assoc($getall);
        $student_id = $student_data['student_id'];
        // Now $student_data and $student_id are available
        // to any file that includes auth.php
    } else {
        // Handle case where student_id in session doesn't match a valid student
        // This is a "ghost" session. Log them out.
        session_unset();
        session_destroy();
        header("Location: login.php?error=invalid_session");
        exit();
    }
}
?>

