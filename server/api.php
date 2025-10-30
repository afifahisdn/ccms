<?php
/*
* api.php
*
* Main API router for the CCMS.
* Receives 'function_code' from JavaScript and executes the corresponding PHP function.
*/

// Start session if not already started
if (session_id() == "") {
    session_start();
}

// Set error reporting for debugging (remove or adjust for production)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Include necessary function files
include_once "inc/connection.php"; // Establishes $con
include_once "inc/get.php";
include_once "inc/add.php";
include_once "inc/update.php";
include_once "inc/delete.php";

// Get the requested function code safely
$function_code = $_GET["function_code"] ?? null;

// Default to JSON response, but can be overridden by specific cases
header('Content-Type: application/json');

try {
    switch ($function_code) {

            // --- Authentication ---
        case "login":
            // getLogin echoes 'admin' (for staff) or 'customer' (for student) directly
            header('Content-Type: text/plain');
            echo getLogin($_POST);
            break;
        case "checkStudentPassword":
            // checkStudentPassword echoes 0 or 1
            header('Content-Type: text/plain');
            checkStudentPassword($_POST);
            break;
        case "checkCurrentStudentEmail":
            // checkCurrentStudentEmail echoes 0 or 1
            header('Content-Type: text/plain');
            checkCurrentStudentEmail($_POST);
            break;
        case "checkStaffPasswordByEmail":
            // checkStaffPasswordByEmail echoes 0 or 1
            header('Content-Type: text/plain');
            checkStaffPasswordByEmail($_POST);
            break;
        case "checkEmailExistsAny":
            // checkEmailExistsAny echoes JSON {exists: true/false}
            checkEmailExistsAny($_POST);
            break;

            // --- Student Management ---
        case "addStudent":
            // createStudent handles both self-registration and admin adding
            // createStudent echoes JSON response {success: true} or {error: ...} or {exists: ...}
            createStudent($_POST);
            break;
        case "getAllStudents":
            // Fetches all students as a PHP array, then encodes as JSON
            $students = getAllStudentDataArray();
            echo json_encode($students ?: []); // Return empty array on failure
            break;
        case "updateStudentProfile":
            // Expects JSON input from homejs.js profileUpdate function
            // updateStudentProfile echoes JSON status {status: 'success'/'error'}
            updateStudentProfile(file_get_contents("php://input"));
            break;

            // --- Staff Management ---
        case "addStaff":
            // addStaff echoes JSON error/exists or returns true
            $result = addStaff($_POST);
            if ($result === true) {
                echo json_encode(["success" => true]);
            }
            // Errors/exists messages are handled inside addStaff()
            break;

            // --- Complaint Management ---
        case "addComplaint":
            // Student submission (handles file upload)
            $result = addComplaint($_POST, $_FILES);
            if ($result === true) {
                echo json_encode(["success" => true]);
            } elseif ($result === false && !headers_sent()) {
                // If addComplaint returned false and didn't send an error
                echo json_encode(["error" => "Failed to submit complaint."]);
            }
            break;
        case "addComplaintAdmin":
            // Admin submission (handles file upload)
            $result = addComplaintAdmin($_POST, $_FILES);
            if ($result === true) {
                echo json_encode(["success" => true]);
            } elseif ($result === false && !headers_sent()) {
                echo json_encode(["error" => "Failed to add complaint."]);
            }
            break;
        case "updateComplaintStatus":
            // updateComplaintStatus echoes 'success' or 'error'
            header('Content-Type: text/plain');
            echo updateComplaintStatus($_POST);
            break;

            // --- Department Management ---
        case "addDepartment":
            $result = addDepartment($_POST);
            if ($result === true) {
                echo json_encode(["success" => true]);
            }
            // Errors/exists messages are handled inside addDepartment()
            break;

            // --- Dormitory Management ---
        case "addDormitory":
            $result = addDormitory($_POST);
            if ($result === true) {
                echo json_encode(["success" => true]);
            }
            // Errors/exists messages are handled inside addDormitory()
            break;

            // --- Feedback Management ---
        case "addFeedback":
            // Called by index.php contact form
            $result = addFeedback($_POST);
            if ($result === true) {
                echo json_encode(["success" => true]);
            } elseif ($result === false && !headers_sent()) {
                echo json_encode(["error" => "Failed to submit feedback."]);
            }
            break;

            // --- Settings Management ---
        case "changesettings":
            // Called by admin/settings.php text inputs
            $result = changePageSettings($_POST);
            echo json_encode(["success" => (bool)$result]);
            break;
        case "SettingImage":
            // Called by admin/settings.php file inputs
            $result = false;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && isset($_POST['field'])) {
                $fieldName = $_POST['field'];
                $fileInfo = $_FILES['file'];

                $target_dir_absolute = __DIR__ . "/uploads/settings/";
                $target_dir_relative = "server/uploads/settings/";

                if (!is_dir($target_dir_absolute)) {
                    if (!mkdir($target_dir_absolute, 0777, true)) {
                        error_log("Failed to create settings upload directory.");
                        echo json_encode(["success" => false, "error" => "Server configuration error (directory)."]);
                        break;
                    }
                }

                $original_filename = basename($fileInfo["name"]);
                $safe_filename = preg_replace("/[^A-Za-z0-9\._-]/", '', $original_filename);
                $file_extension = strtolower(pathinfo($safe_filename, PATHINFO_EXTENSION));
                $final_filename = $fieldName . '.' . $file_extension; // Overwrite existing (e.g., header_image.png)

                $target_file_absolute = $target_dir_absolute . $final_filename;
                $target_file_relative = $target_dir_relative . $final_filename;

                $allowed_extensions = ["jpg", "jpeg", "png", "gif", "svg", "webp"];
                $max_file_size = 5 * 1024 * 1024; // 5MB limit

                if (in_array($file_extension, $allowed_extensions) && ($fileInfo["size"] <= $max_file_size)) {
                    if (move_uploaded_file($fileInfo["tmp_name"], $target_file_absolute)) {
                        $updatePostData = ['field' => $fieldName];
                        $result = editSettingImage($updatePostData, $target_file_relative);
                    } else {
                        error_log("Failed to move uploaded settings image to " . $target_file_absolute);
                        echo json_encode(["success" => false, "error" => "Failed to save uploaded file."]);
                        break;
                    }
                } else {
                    echo json_encode(["success" => false, "error" => "Invalid file type or file is too large (Max 5MB)."]);
                    break;
                }
            } else {
                $upload_error_code = $_FILES['file']['error'] ?? 'No file';
                error_log("No file uploaded or upload error for settings image. Error code: " . $upload_error_code);
                echo json_encode(["success" => false, "error" => "No file uploaded or upload error."]);
                break;
            }
            echo json_encode(["success" => (bool)$result]);
            break;

            // --- General Data Operations ---
        case "updateData":
            // Generic update for inline edits (e.g., complaint status)
            $result = updateDataTable($_POST);
            echo json_encode(["success" => (bool)$result]);
            break;
        case "deleteData":
            // Soft delete (sets is_deleted = 1)
            $result = deleteDataTables($_POST);
            echo json_encode(["success" => (bool)$result]);
            break;
        case "permanantDeleteData":
            // Hard delete (DELETE FROM table) - expects JSON input
            permanantDeleteDataTable(file_get_contents("php://input")); // Echos JSON
            break;

            // --- Default Case ---
        default:
            error_log("Unknown API function code requested: " . $function_code);
            echo json_encode(["error" => "Invalid function code specified."]);
            break;
    }
} catch (Exception $e) {
    // Catch any unexpected PHP errors
    error_log("API Exception in api.php: " . $e->getMessage());
    if (!headers_sent()) {
        echo json_encode(["error" => "An internal server error occurred."]);
    }
}
?>