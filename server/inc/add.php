<?php
/*
* add.php
*
* Contains all functions for ADDING data to the CCMS database.
*/

include_once "connection.php";
include_once "get.php"; // Include get.php for check functions

/**
 * Adds a new complaint category to the database.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addCategory($data)
{
    include "connection.php";

    $category_name = mysqli_real_escape_string($con, $data["category_name"]);

    $count = checkCategoryByName($category_name);

    if ($count == 0) {
        $sql = "INSERT INTO categories(category_name, is_deleted) VALUES(?, 0)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $category_name);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                error_log("Error adding category: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                echo json_encode(["error" => "Database error adding category."]);
                return false;
            }
        } else {
            error_log("Error preparing addCategory statement: " . mysqli_error($con));
            echo json_encode(["error" => "Database error."]);
            return false;
        }
    } else if ($count > 0) {
        echo json_encode(["exists" => true, "message" => "This Category Name Already Exists."]);
        return false;
    } else {
        echo json_encode(["error" => "Error checking category name."]);
        return false;
    }
}

/**
 * Adds a new department to the database.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addDepartment($data)
{
    include "connection.php";

    $department_name = mysqli_real_escape_string($con, $data["department_name"]);
    $department_type = mysqli_real_escape_string($con, $data["department_type"]);

    $valid_types = ['maintenance', 'it', 'cleaning', 'security', 'administration'];
    if (!in_array($department_type, $valid_types)) {
        echo json_encode(["error" => "Invalid department type"]);
        return false;
    }

    $count = checkDepartmentByName($department_name);

    if ($count == 0) {
        $sql = "INSERT INTO department(department_name, department_type, is_deleted) VALUES(?, ?, 0)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $department_name, $department_type);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                error_log("Error adding department: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                echo json_encode(["error" => "Database error adding department."]);
                return false;
            }
        } else {
            error_log("Error preparing addDepartment statement: " . mysqli_error($con));
            echo json_encode(["error" => "Database error."]);
            return false;
        }
    } else if ($count > 0) {
        echo json_encode(["exists" => true, "message" => "This Department Name Already Exists."]);
        return false;
    } else {
        echo json_encode(["error" => "Error checking department name."]);
        return false;
    }
}

/**
 * Adds a new dormitory to the database.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addDormitory($data)
{
    include "connection.php";

    $dormitory_name = mysqli_real_escape_string($con, $data["dormitory_name"]);
    $dormitory_code = mysqli_real_escape_string($con, $data["dormitory_code"]);

    $count = checkDormitoryByNameOrCode($dormitory_name, $dormitory_code);

    if ($count == 0) {
        $sql = "INSERT INTO dormitory(dormitory_name, dormitory_code, is_deleted) VALUES(?, ?, 0)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $dormitory_name, $dormitory_code);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                error_log("Error adding dormitory: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                echo json_encode(["error" => "Database error adding dormitory."]);
                return false;
            }
        } else {
            error_log("Error preparing addDormitory statement: " . mysqli_error($con));
            echo json_encode(["error" => "Database error."]);
            return false;
        }
    } else if ($count > 0) {
        echo json_encode(["exists" => true, "message" => "This Dormitory Name or Code Already Exists."]);
        return false;
    } else {
        echo json_encode(["error" => "Error checking dormitory name/code."]);
        return false;
    }
}

/**
 * Adds a new complaint submitted by a student (handles photo upload).
 *
 * @param array $data Asssociative array from $_POST.
 * @param array $file Asssociative array from $_FILES.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addComplaint($data, $file)
{
    include "connection.php";

    $student_id = mysqli_real_escape_string($con, $data["student_id"]); // This is now a VARCHAR
    $dormitory_id = mysqli_real_escape_string($con, $data["dormitory_id"]);
    $room_number = mysqli_real_escape_string($con, $data["room_number"]);
    $complaint_title = mysqli_real_escape_string($con, $data["complaint_title"]);
    $category_id = mysqli_real_escape_string($con, $data["category_id"]); // This is now an INT ID
    $urgency_level = mysqli_real_escape_string($con, $data["urgency_level"]);
    $complaint_description = mysqli_real_escape_string($con, $data["complaint_description"]);

    $photo_path_db = NULL;
    $photo_upload_success = true;

    // Handle file upload
    if (isset($file["photo"]) && $file["photo"]["error"] == UPLOAD_ERR_OK && $file["photo"]["size"] > 0) {
        $target_dir_absolute = __DIR__ . "/../uploads/complaints/";
        $target_dir_relative = "server/uploads/complaints/";

        if (!is_dir($target_dir_absolute)) {
            if (!mkdir($target_dir_absolute, 0777, true)) {
                error_log("Failed to create complaints upload directory.");
                echo json_encode(["error" => "Server error: Cannot create upload directory."]);
                return false;
            }
        }

        $original_filename = basename($file["photo"]["name"]);
        $safe_filename = preg_replace("/[^A-Za-z0-9\._-]/", '', $original_filename);
        $file_extension = strtolower(pathinfo($safe_filename, PATHINFO_EXTENSION));
        $unique_filename = uniqid('complaint_', true) . '.' . $file_extension;

        $target_file_absolute = $target_dir_absolute . $unique_filename;
        $target_file_relative = $target_dir_relative . $unique_filename;

        $extensions_arr = ["jpg", "jpeg", "png", "gif"];
        if (in_array($file_extension, $extensions_arr)) {
            if ($file["photo"]["size"] < 5000000) { // Max 5MB
                if (move_uploaded_file($file["photo"]["tmp_name"], $target_file_absolute)) {
                    $photo_path_db = $target_file_relative;
                } else {
                    error_log("Error moving uploaded file for complaint.");
                    $photo_upload_success = false;
                }
            } else {
                error_log("Uploaded file size exceeds 5MB limit.");
                $photo_upload_success = false;
            }
        } else {
            error_log("Invalid file type uploaded for complaint.");
            $photo_upload_success = false;
        }
    } elseif (isset($file["photo"]) && $file["photo"]["error"] != UPLOAD_ERR_NO_FILE) {
        error_log("File upload error code: " . $file["photo"]["error"]);
        $photo_upload_success = false;
    }

    if ($photo_upload_success) {
        // --- UPDATED SQL ---
        // `category_id` is now an INT
        // `complaint_status` is now an ENUM (set to 'Open')
        $sql = "INSERT INTO complaint(student_id, dormitory_id, category_id, room_number, complaint_title, complaint_description, photo, urgency_level, complaint_status, created_at, date_updated) 
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, 'Open', NOW(), NOW())";

        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            // `student_id` (s), `dormitory_id` (i), `category_id` (i)
            mysqli_stmt_bind_param(
                $stmt,
                "siisssss", // s=string, i=int
                $student_id,
                $dormitory_id,
                $category_id, // <-- UPDATED
                $room_number,
                $complaint_title,
                $complaint_description,
                $photo_path_db,
                $urgency_level
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                error_log("Error executing complaint insert statement: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return false;
            }
        } else {
            error_log("Error preparing complaint insert statement: " . mysqli_error($con));
            return false;
        }
    } else {
        echo json_encode(["error" => "Photo upload failed. Please check file type (JPG, PNG, GIF) and size (max 5MB)."]);
        return false;
    }
}


/**
 * Adds a new complaint submitted via the Admin panel.
 * Reuses the addComplaint logic.
 */
function addComplaintAdmin($data, $file)
{
    // This function is identical to addComplaint
    return addComplaint($data, $file);
}


/**
 * Adds a new staff member to the database.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addStaff($data)
{
    include "connection.php";

    $name = mysqli_real_escape_string($con, $data["name"]);
    $email = mysqli_real_escape_string($con, $data["email"]);
    $phone = mysqli_real_escape_string($con, $data["phone"]);
    $nric = mysqli_real_escape_string($con, $data["nric"]);
    $address = mysqli_real_escape_string($con, $data["address"]);
    $gender = mysqli_real_escape_string($con, $data["gender"]);
    $password = mysqli_real_escape_string($con, $data["password"]);
    $department_id = mysqli_real_escape_string($con, $data["department_id"]);
    $staff_role = mysqli_real_escape_string($con, $data["staff_role"]);

    if (!in_array($staff_role, ['admin', 'staff'])) {
        echo json_encode(["error" => "Invalid staff role specified"]);
        return false;
    }
    if (!in_array($gender, ['1', '2'])) {
        echo json_encode(["error" => "Invalid gender specified"]);
        return false;
    }

    $count = checkStaffOrStudentByEmail($email);

    if ($count == 0) {
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO staff(name, email, phone, nric, address, gender, password, is_deleted, department_id, staff_role) 
                VALUES(?, ?, ?, ?, ?, ?, ?, 0, ?, ?)";

        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssssssis",
                $name,
                $email,
                $phone,
                $nric,
                $address,
                $gender,
                $password, // Use $hashed_password here
                $department_id,
                $staff_role
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                error_log("Error executing staff insert statement: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                echo json_encode(["error" => "Database error adding staff."]);
                return false;
            }
        } else {
            error_log("Error preparing staff insert statement: " . mysqli_error($con));
            echo json_encode(["error" => "Database error preparing statement."]);
            return false;
        }
    } else if ($count > 0) {
        echo json_encode(["exists" => true, "message" => "This Email Address is Already Registered!"]);
        return false;
    } else {
        echo json_encode(["error" => "Error checking email."]);
        return false;
    }
}

/**
 * Adds feedback from a logged-in student.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure (or echoes JSON error).
 */
function addFeedback($data)
{
    include "connection.php";

    $student_id = mysqli_real_escape_string($con, $data["student_id"]); // VARCHAR
    $subject = mysqli_real_escape_string($con, $data["subject"]);
    $message = mysqli_real_escape_string($con, $data["message"]);

    $sql = "INSERT INTO feedback(student_id, subject, message, date_updated, is_deleted) VALUES(?, ?, ?, NOW(), 0)";

    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $student_id, $subject, $message); // 's' for VARCHAR student_id
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error executing feedback insert statement: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            echo json_encode(["error" => "Database error adding feedback."]);
            return false;
        }
    } else {
        error_log("Error preparing feedback insert statement: " . mysqli_error($con));
        echo json_encode(["error" => "Database error preparing statement."]);
        return false;
    }
}

/**
 * Creates a new student account (registration).
 *
 * @param array $data Asssociative array from form post.
 * @return void Echos JSON response.
 */
function createStudent($data)
{
    include 'connection.php';

    $name = mysqli_real_escape_string($con, $data['name']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $phone = mysqli_real_escape_string($con, $data['phone']);
    $address = mysqli_real_escape_string($con, $data['address']);
    $gender = mysqli_real_escape_string($con, $data['gender']);
    $password = mysqli_real_escape_string($con, $data['password']);
    $student_id = mysqli_real_escape_string($con, $data['student_id_number']); // Use student_id_number as the PK
    $room_number = mysqli_real_escape_string($con, $data['room_number']);

    if (!in_array($gender, ['1', '2'])) {
        echo json_encode(["error" => true, "message" => "Invalid gender."]);
        return;
    }

    // Check if email or student ID (PK) already exists
    $checkSql = "SELECT email, student_id FROM student WHERE (email = ? OR student_id = ?) AND is_deleted = 0";
    $checkStmt = mysqli_prepare($con, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "ss", $email, $student_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        $existing = mysqli_fetch_assoc($checkResult);
        mysqli_stmt_close($checkStmt);
        if ($existing['email'] == $email) {
            echo json_encode(["exists" => true, "message" => "Email already exists."]);
        } else {
            echo json_encode(["exists" => true, "message" => "Student ID Number already exists."]);
        }
        return;
    }
    mysqli_stmt_close($checkStmt);

    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // --- UPDATED SQL: Save student_id_number into student_id (PK) ---
    $insertSql = "INSERT INTO student(student_id, name, email, phone, address, gender, password, room_number, is_deleted) 
                  VALUES(?, ?, ?, ?, ?, ?, ?, ?, 0)";

    $insertStmt = mysqli_prepare($con, $insertSql);
    if ($insertStmt) {
        mysqli_stmt_bind_param(
            $insertStmt,
            "ssssssss",
            $student_id, // This is the new VARCHAR PK
            $name,
            $email,
            $phone,
            $address,
            $gender,
            $password, // Use $hashed_password
            $room_number
        );

        if (mysqli_stmt_execute($insertStmt)) {
            echo json_encode(["success" => true, "message" => "Student created successfully."]);
        } else {
            error_log("Error creating student: " . mysqli_stmt_error($insertStmt));
            echo json_encode(["error" => true, "message" => "Error creating student profile."]);
        }
        mysqli_stmt_close($insertStmt);
    } else {
        error_log("Error preparing student insert statement: " . mysqli_error($con));
        echo json_encode(["error" => true, "message" => "Database error preparing statement."]);
    }
}

?>