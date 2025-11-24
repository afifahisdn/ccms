<?php
/*
* update.php
*
* Contains functions for UPDATING data in the CCMS database.
*/

// Use include_once for safety
include_once "connection.php";

/**
 * "Poor Man's Cron" - Auto-closes complaints.
 * Finds all complaints with 'Resolved' status older than 1 WEEK
 * and updates them to 'Closed'.
 *
 * @return int Number of rows affected, or -1 on error.
 */
function autoCloseComplaints()
{
    include 'connection.php';
    
    // UPDATED: Check for 1 WEEK instead of 24 hours
    $sql = "UPDATE complaint 
            SET 
                complaint_status = 'Closed',
                date_updated = NOW()
            WHERE 
                complaint_status = 'Resolved'
                AND date_resolved <= NOW() - INTERVAL 1 WEEK
                AND is_deleted = 0";

    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            // Log if you want: error_log("Auto-close cron: $affected_rows complaints closed.");
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
 */
function updateDataTable($data, $staff_id, $role)
{
    include 'connection.php';

    $id_fild = mysqli_real_escape_string($con, $data['id_fild']);
    $id = mysqli_real_escape_string($con, $data['id']);
    $field = mysqli_real_escape_string($con, $data['field']);
    $value = mysqli_real_escape_string($con, $data['value']);
    $table = mysqli_real_escape_string($con, $data['table']);

    // Security check for table/field names
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $id_fild) || 
        !preg_match('/^[a-zA-Z0-9_]+$/', $field) || 
        !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return false;
    }

    // --- Special Logic for CCMS ---
    $additional_sql = "";
    $param_types = "ss"; 
    $params = [$value, $id];

    // 1. Handle NULL values (e.g., unassigning staff)
    // If the value is an empty string and the field is a foreign key that allows NULL
    if ($value === "" && ($field == 'assigned_staff_id' || $field == 'student_id')) {
        $value = NULL; // Set PHP variable to NULL
    }

    // 2. Update 'date_updated' for complaints
    if ($table == 'complaint') {
        $additional_sql .= ", date_updated = NOW()";
    }

    // 3. Handle 'complaint_status' logic (date_resolved)
    if ($table == 'complaint' && $field == 'complaint_status') {
        if ($value == 'Resolved' || $value == 'Closed') {
            $additional_sql .= ", date_resolved = NOW()";
        } else {
            $additional_sql .= ", date_resolved = NULL";
        }
    }

    // 4. Auto-Assign Logic (remains the same)
    if ($table == 'complaint' && $role == 'staff' && $staff_id > 0 && $field == 'complaint_status') {
        $check_sql = "SELECT assigned_staff_id FROM complaint WHERE $id_fild = ?";
        $check_stmt = mysqli_prepare($con, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        $complaint_row = mysqli_fetch_assoc($check_result);
        mysqli_stmt_close($check_stmt);

        if ($complaint_row && $complaint_row['assigned_staff_id'] === NULL) {
            $additional_sql .= ", assigned_staff_id = " . (int)$staff_id;
        }
    }

    // 5. Determine param types
    if ($id_fild == 'student_id') {
        $param_types = "ss"; 
    } elseif (in_array($id_fild, ['complaint_id', 'staff_id', 'department_id', 'dormitory_id', 'category_id', 'feedback_id'])) {
        $param_types = "si"; 
    }
    
    // Construct Query
    $sql = "UPDATE `$table` SET `$field` = ? $additional_sql WHERE `$id_fild` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        // Bind parameters. If $value is NULL, bind it correctly.
        // Note: mysqli_stmt_bind_param requires variables passed by reference.
        // We reuse the same types string 'ss' or 'si' generally works, but for true NULL handling
        // we might need to be specific. However, passing a NULL variable to bind_param usually works.
        
        mysqli_stmt_bind_param($stmt, $param_types, $value, $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } 
    } 
    return false;
}

/**
 * Updates an image field path in a table.
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
        mysqli_stmt_bind_param($stmt, "ss", $img_path_sanitized, $id); // Assume ID is string-like
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
 * Also handles updating date_resolved.
 */
function updateComplaintStatus($data)
{
    include 'connection.php';

    $complaint_id = mysqli_real_escape_string($con, $data['complaint_id']);
    $complaint_status = mysqli_real_escape_string($con, $data['complaint_status']);

    // Validate status value (Withdrawn is now effectively handled as Closed if you changed the logic,
    // but keeping it in the ENUM for flexibility is fine. The JS sends 'Closed' now.)
    $valid_statuses = ['Open', 'In Progress', 'Resolved', 'Closed', 'Withdrawn'];
    if (!in_array($complaint_status, $valid_statuses)) {
        return 'error';
    }

    // Set date_resolved if status is Resolved, Closed, or Withdrawn
    $date_resolved_sql = ($complaint_status == 'Resolved' || $complaint_status == 'Closed' || $complaint_status == 'Withdrawn') ? ", date_resolved = NOW()" : "";
    
    // Clear resolved date if moving back to Open/In Progress
    if (in_array($complaint_status, ['Open', 'In Progress'])) {
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
    $gender = mysqli_real_escape_string($con, $data['new_gender'] ?? '');
    $room_number = mysqli_real_escape_string($con, $data['room_number'] ?? '');

    // Basic validation
    if (empty($name) || empty($phone) || empty($gender) || empty($room_number)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return;
    }
    if (!in_array($gender, ['1', '2'])) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid gender.']);
        return;
    }

    // No duplicate check needed since email/student_id are not editable here
    
    $sql = "UPDATE student SET name = ?, phone = ?, gender = ?, room_number = ? WHERE student_id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // s(name), s(phone), s(gender), s(room_number), s(student_id)
        $stmt->bind_param("sssss", $name, $phone, $gender, $room_number, $student_id);

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

/**
 * Updates a staff member's full profile.
 * Includes validation for required fields.
 */
function updateStaffProfile($data) {
    include 'connection.php';
    
    $staff_id = mysqli_real_escape_string($con, $data['staff_id']);
    $name = mysqli_real_escape_string($con, $data['name']);
    $email = mysqli_real_escape_string($con, $data['email']);
    $phone = mysqli_real_escape_string($con, $data['phone']);
    $nric = mysqli_real_escape_string($con, $data['nric']);
    $gender = mysqli_real_escape_string($con, $data['gender']);
    $department_id = mysqli_real_escape_string($con, $data['department_id']);
    $staff_role = mysqli_real_escape_string($con, $data['staff_role']);

    // --- VALIDATION ---
    if (empty($name) || empty($phone) || empty($email) || empty($nric)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        return; // Stop execution
    }

    $sql = "UPDATE staff SET name = ?, email = ?, phone = ?, nric = ?, gender = ?, department_id = ?, staff_role = ? WHERE staff_id = ?";
            
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssiisi", $name, $email, $phone, $nric, $gender, $department_id, $staff_role, $staff_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
             // Echo JSON for success
            echo json_encode(['success' => true]); 
            return;
        }
        mysqli_stmt_close($stmt);
    }
    echo json_encode(['error' => 'Database error updating staff profile.']);
}

?>