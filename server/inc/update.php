<?php
/*
* update.php
*
* Contains functions for UPDATING data in the CCMS database.
*/

// Include database connection
include_once "connection.php"; // Use include_once to prevent multiple includes

/**
 * Generic function to update a single field in any table.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure.
 */
function updateDataTable($data)
{
    include 'connection.php'; // Ensure $con is available

    // Basic sanitization
    $id_fild = mysqli_real_escape_string($con, $data['id_fild']);
    $id = mysqli_real_escape_string($con, $data['id']);
    $field = mysqli_real_escape_string($con, $data['field']);
    $value = mysqli_real_escape_string($con, $data['value']);
    $table = mysqli_real_escape_string($con, $data['table']);

    // Ensure table/field names are valid (basic check)
    if (
        !preg_match('/^[a-zA-Z0-9_]+$/', $id_fild) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $field) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $table)
    ) {
        error_log("Invalid table/field name for update: table=$table, field=$field, id_field=$id_fild");
        return false;
    }

    // Add logic to update `date_updated` for complaints
    $update_time_sql = ($table == 'complaint') ? ", date_updated = NOW()" : "";

    // Special check for complaint_status to set date_resolved
    if ($table == 'complaint' && $field == 'complaint_status') {
        if ($value == '3' || $value == '4') { // If status is Resolved or Closed
             $update_time_sql .= ", date_resolved = NOW()";
        } else {
             $update_time_sql .= ", date_resolved = NULL"; // Clear resolved date if moving back to Open/In Progress
        }
    }


    $sql = "UPDATE `$table` SET `$field` = ? $update_time_sql WHERE `$id_fild` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $value, $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error executing update statement for table $table: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error preparing update statement for table $table: " . mysqli_error($con));
        return false;
    }
}

/**
 * Updates an image field path in a table.
 * Assumes the image file has already been uploaded and moved.
 *
 * @param array $data Asssociative array from form post.
 * @param string $img_path The relative path of the uploaded image.
 * @return bool True on success, false on failure.
 */
function editImages($data, $img_path)
{
    include 'connection.php';

    $id_field = mysqli_real_escape_string($con, $data["id_field"]);
    $id = mysqli_real_escape_string($con, $data["id"]);
    $field = mysqli_real_escape_string($con, $data["field"]);
    $table = mysqli_real_escape_string($con, $data["table"]);
    $img_path_sanitized = mysqli_real_escape_string($con, $img_path);

    if (
        !preg_match('/^[a-zA-Z0-9_]+$/', $id_field) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $field) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $table)
    ) {
        error_log("Invalid table/field name for image update: table=$table, field=$field, id_field=$id_field");
        return false;
    }

    $sql = "UPDATE `$table` SET `$field` = ? WHERE `$id_field` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $img_path_sanitized, $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error executing image update statement for table $table: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error preparing image update statement for table $table: " . mysqli_error($con));
        return false;
    }
}

/**
 * Updates a single field in the settings table.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure.
 */
function changePageSettings($data)
{
    include 'connection.php';

    $field = mysqli_real_escape_string($con, $data["field"]);
    $value = mysqli_real_escape_string($con, $data["value"]);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $field)) {
        error_log("Invalid field name for settings update: $field");
        return false;
    }

    $sql = "UPDATE settings SET `$field` = ? LIMIT 1"; // Assumes one row
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $value);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error executing settings update statement: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error preparing settings update statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Updates an image field path in the settings table.
 *
 * @param array $data Asssociative array from form post.
 * @param string $img_path The relative path of the uploaded image.
 * @return bool True on success, false on failure.
 */
function editSettingImage($data, $img_path)
{
    include 'connection.php';

    $field = mysqli_real_escape_string($con, $data["field"]);
    $img_path_sanitized = mysqli_real_escape_string($con, $img_path);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $field)) {
        error_log("Invalid field name for settings image update: $field");
        return false;
    }

    $sql = "UPDATE settings SET `$field` = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $img_path_sanitized);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error executing settings image update statement: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error preparing settings image update statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Updates the status of a complaint.
 *
 * @param array $data Asssociative array from form post.
 * @return string 'success' or 'error'.
 */
function updateComplaintStatus($data)
{
    include 'connection.php';

    $complaint_id = mysqli_real_escape_string($con, $data['complaint_id']);
    $complaint_status = mysqli_real_escape_string($con, $data['complaint_status']);

    // Validate status code
    if (!in_array($complaint_status, ['1', '2', '3', '4', '5'])) { // Added 5 for 'Withdrawn'
        error_log("Invalid complaint status received: " . $complaint_status);
        return 'error';
    }
    
    // Set date_resolved if status is Resolved (3) or Closed (4)
    $date_resolved_sql = ($complaint_status == '3' || $complaint_status == '4') ? ", date_resolved = NOW()" : "";
    
    // Clear resolved date if moving back to Open/In Progress
    if (in_array($complaint_status, ['1', '2', '5'])) {
         $date_resolved_sql = ", date_resolved = NULL"; 
    }

    $sql = "UPDATE complaint SET complaint_status = ?, date_updated = NOW() $date_resolved_sql WHERE complaint_id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $complaint_status, $complaint_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return 'success';
        } else {
            error_log("Error executing complaint status update: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return 'error';
        }
    } else {
        error_log("Error preparing complaint status update statement: " . mysqli_error($con));
        return 'error';
    }
}

/**
 * Updates student profile details.
 *
 * @param string $jsonData JSON string containing student data.
 * @return void Echos JSON response.
 */
function updateStudentProfile($jsonData)
{
    include 'connection.php';

    $data = json_decode($jsonData, true);

    if (!$data || !isset($data['student_id'])) {
        error_log("Invalid data received for student profile update.");
        echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
        return;
    }

    // Sanitize data
    $student_id = mysqli_real_escape_string($con, $data['student_id']);
    $name = mysqli_real_escape_string($con, $data['new_name'] ?? '');
    $phone = mysqli_real_escape_string($con, $data['new_phone'] ?? '');
    $address = mysqli_real_escape_string($con, $data['new_address'] ?? '');
    $gender = mysqli_real_escape_string($con, $data['new_gender'] ?? '');
    $student_id_number = mysqli_real_escape_string($con, $data['student_id_number'] ?? '');
    $room_number = mysqli_real_escape_string($con, $data['room_number'] ?? '');

    // Basic validation
    if (empty($name) || empty($phone) || empty($address) || empty($gender) || empty($student_id_number) || empty($room_number)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return;
    }
    if (!in_array($gender, ['1', '2'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid gender.']);
        return;
    }

    // Check if student ID number is already taken by *another* student
    $checkSql = "SELECT student_id FROM student WHERE student_id_number = ? AND student_id != ?";
    $checkStmt = mysqli_prepare($con, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "si", $student_id_number, $student_id);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);
    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        mysqli_stmt_close($checkStmt);
        echo json_encode(['status' => 'error', 'message' => 'Student ID Number is already in use by another student.']);
        return;
    }
    mysqli_stmt_close($checkStmt);

    $sql = "UPDATE student SET name = ?, phone = ?, address = ?, gender = ?, student_id_number = ?, room_number = ? WHERE student_id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssi", $name, $phone, $address, $gender, $student_id_number, $room_number, $student_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            error_log("Error updating student profile: " . $stmt->error);
            echo json_encode(['status' => 'error', 'message' => 'Database error updating profile.']);
        }
        $stmt->close();
    } else {
        error_log("Error preparing student profile update statement: " . $con->error);
        echo json_encode(['status' => 'error', 'message' => 'Database error preparing statement.']);
    }
}

?>