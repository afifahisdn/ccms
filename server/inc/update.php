<?php
/*
* update.php
*
* Contains functions for UPDATING data in the CCMS database.
* and new business logic.
*/

// Use include_once for safety
include_once "connection.php";

/**
 * "Poor Man's Cron" - Auto-closes complaints.
 * Finds all complaints with 'Resolved' status older than 24 hours
 * and updates them to 'Closed'.
 *
 * @return int Number of rows affected, or -1 on error.
 */
function autoCloseComplaints()
{
    include 'connection.php';
    
    // We update `date_updated` and `date_resolved`
    // `date_resolved` is set when status becomes 'Resolved'
    $sql = "UPDATE complaint 
            SET 
                complaint_status = 'Closed',
                date_updated = NOW()
            WHERE 
                complaint_status = 'Resolved'
                AND date_resolved <= NOW() - INTERVAL 24 HOUR
                AND is_deleted = 0";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            error_log("Auto-close cron: $affected_rows complaints closed.");
            return $affected_rows;
        } else {
            error_log("Error executing autoCloseComplaints: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return -1;
        }
    } else {
        error_log("Error preparing autoCloseComplaints statement: " . mysqli_error($con));
        return -1;
    }
}


/**
 * Generic function to update a single field in any table.
 * Now with added logic for complaint timestamps.
 *
 * @param array $data Asssociative array from form post.
 * @return bool True on success, false on failure.
 */
function updateDataTable($data)
{
    include 'connection.php';

    $id_fild = mysqli_real_escape_string($con, $data['id_fild']);
    $id = mysqli_real_escape_string($con, $data['id']);
    $field = mysqli_real_escape_string($con, $data['field']);
    $value = mysqli_real_escape_string($con, $data['value']);
    $table = mysqli_real_escape_string($con, $data['table']);

    // Ensure table/field names are valid
    if (
        !preg_match('/^[a-zA-Z0-9_]+$/', $id_fild) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $field) ||
        !preg_match('/^[a-zA-Z0-9_]+$/', $table)
    ) {
        error_log("Invalid table/field name for update: table=$table, field=$field, id_field=$id_fild");
        return false;
    }

    // --- Special Logic for CCMS ---
    $additional_sql = "";
    $param_types = "ss"; // $value (s), $id (s - safer default)
    $params = [$value, $id];

    // 1. If updating anything in 'complaint' table, update 'date_updated'
    if ($table == 'complaint') {
        $additional_sql .= ", date_updated = NOW()";
    }

    // 2. If updating 'complaint_status' specifically
    if ($table == 'complaint' && $field == 'complaint_status') {
        // Use the new ENUM string values
        if ($value == 'Resolved' || $value == 'Closed') {
            $additional_sql .= ", date_resolved = NOW()";
        } else {
            // If status is set back to Open, In Progress, or Withdrawn, clear the resolved date
            $additional_sql .= ", date_resolved = NULL";
        }
    }
    
    // 3. Determine param type for ID
    if ($id_fild == 'student_id') {
        $param_types = "ss"; // $value (s), $id (s)
    } elseif (in_array($id_fild, ['complaint_id', 'staff_id', 'department_id', 'dormitory_id', 'category_id', 'feedback_id'])) {
        $param_types = "si"; // $value (s), $id (i)
    }

    // --- End Special Logic ---

    $sql = "UPDATE `$table` SET `$field` = ? $additional_sql WHERE `$id_fild` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $param_types, ...$params);

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
        // Assume ID is an integer for this generic function, may need adjustment
        mysqli_stmt_bind_param($stmt, "si", $img_path_sanitized, $id); 
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

    $sql = "UPDATE settings SET `$field` = ? LIMIT 1";
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
 * Updates the status of a complaint (using new ENUM values).
 *
 * @param array $data Asssociative array from form post.
 * @return string 'success' or 'error'.
 */
function updateComplaintStatus($data)
{
    include 'connection.php';

    $complaint_id = mysqli_real_escape_string($con, $data['complaint_id']);
    $complaint_status = mysqli_real_escape_string($con, $data['complaint_status']);

    // Validate status value against the ENUM
    $valid_statuses = ['Open', 'In Progress', 'Resolved', 'Closed', 'Withdrawn'];
    if (!in_array($complaint_status, $valid_statuses)) {
        error_log("Invalid complaint status received: " . $complaint_status);
        return 'error';
    }

    // Set date_resolved if status is Resolved or Closed
    $date_resolved_sql = ($complaint_status == 'Resolved' || $complaint_status == 'Closed') ? ", date_resolved = NOW()" : "";

    // Clear resolved date if moving back to Open/In Progress/Withdrawn
    if (in_array($complaint_status, ['Open', 'In Progress', 'Withdrawn'])) {
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
    $student_id = mysqli_real_escape_string($con, $data['student_id']); // This is the PK (VARCHAR)
    $name = mysqli_real_escape_string($con, $data['new_name'] ?? '');
    $phone = mysqli_real_escape_string($con, $data['new_phone'] ?? '');
    $address = mysqli_real_escape_string($con, $data['new_address'] ?? '');
    $gender = mysqli_real_escape_string($con, $data['new_gender'] ?? '');
    $room_number = mysqli_real_escape_string($con, $data['room_number'] ?? '');
    // $student_id_number is NO LONGER received, as it's non-editable.

    // Basic validation
    if (empty($name) || empty($phone) || empty($address) || empty($gender) || empty($room_number)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return;
    }
    if (!in_array($gender, ['1', '2'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid gender.']);
        return;
    }
    
    // We don't check for duplicate Student ID because it's the PK and cannot be changed.
    // We only need to check if the EMAIL is being changed, and if so, if it's a duplicate.
    // But this function doesn't update email (that's changeStudentEmail).
    // So, no duplicate check is needed here.

    $sql = "UPDATE student SET name = ?, phone = ?, address = ?, gender = ?, room_number = ? WHERE student_id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $name, $phone, $address, $gender, $room_number, $student_id);

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