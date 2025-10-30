<?php
/*
* get.php
*
* Contains all functions for retrieving data from the CCMS database.
*/

// ===================================
// Department Functions
// ===================================

/**
 * Gets all non-deleted departments.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllDepartment()
{
    include "connection.php";
    $sql = "SELECT * FROM department WHERE is_deleted = 0 ORDER BY department_name ASC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching departments: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Gets a specific department by its ID.
 * @param int $department_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getDepartmentByID($department_id)
{
    include "connection.php";
    $sql = "SELECT * FROM department WHERE is_deleted = 0 AND department_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $department_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getDepartmentByID statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Checks if a department name already exists (case-insensitive).
 * @param string $department_name
 * @return int Count (0 or 1), or -1 on error.
 */
function checkDepartmentByName($department_name)
{
    include "connection.php";
    $sql = "SELECT department_id FROM department WHERE LOWER(department_name) = LOWER(?) AND is_deleted = 0";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $department_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $count;
    } else {
        error_log("Error preparing checkDepartmentByName statement: " . mysqli_error($con));
        return -1; // Indicate error
    }
}


// ===================================
// Dormitory Functions
// ===================================

/**
 * Gets all non-deleted dormitories.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllDormitory()
{
    include "connection.php";
    $sql = "SELECT * FROM dormitory WHERE is_deleted = 0 ORDER BY dormitory_name ASC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching dormitories: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Gets a specific dormitory by its ID.
 * @param int $dormitory_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getDormitoryByID($dormitory_id)
{
    include "connection.php";
    $sql = "SELECT * FROM dormitory WHERE is_deleted = 0 AND dormitory_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $dormitory_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getDormitoryByID statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Checks if a dormitory name OR code already exists (case-insensitive).
 * @param string $dormitory_name
 * @param string $dormitory_code
 * @return int Count (0 or 1), or -1 on error.
 */
function checkDormitoryByNameOrCode($dormitory_name, $dormitory_code)
{
    include "connection.php";
    $sql = "SELECT dormitory_id FROM dormitory 
            WHERE (LOWER(dormitory_name) = LOWER(?) OR LOWER(dormitory_code) = LOWER(?)) 
            AND is_deleted = 0";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $dormitory_name, $dormitory_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $count;
    } else {
        error_log("Error preparing checkDormitoryByNameOrCode statement: " . mysqli_error($con));
        return -1;
    }
}


// ===================================
// Complaint Functions
// ===================================

/**
 * Gets all complaints submitted by a specific student.
 * Joins with dormitory table.
 * @param int $student_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllComplaintsByStudentID($student_id)
{
    include "connection.php";
    $sql = "SELECT c.*, d.dormitory_name 
            FROM complaint c
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id
            WHERE c.is_deleted = 0 AND c.student_id = ? 
            ORDER BY c.created_at DESC"; // Order by most recent
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getAllComplaintsByStudentID statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Gets a specific complaint by its ID for public status check.
 * Joins with dormitory, student, and staff tables.
 * @param int $complaint_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getComplaintByID($complaint_id)
{
    include "connection.php";
    $sql = "SELECT c.*, d.dormitory_name, s.name as student_name, st.name as staff_name
            FROM complaint c
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id
            LEFT JOIN student s ON c.student_id = s.student_id
            LEFT JOIN staff st ON c.assigned_staff_id = st.staff_id 
            WHERE c.is_deleted = 0 AND c.complaint_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $complaint_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getComplaintByID statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Gets all non-deleted complaints for the admin/staff view.
 * Joins with student and dormitory tables.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllComplaints()
{
    include "connection.php";
    $sql = "SELECT c.*, s.name as student_name, d.dormitory_name 
            FROM complaint c 
            LEFT JOIN student s ON c.student_id = s.student_id 
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id 
            WHERE c.is_deleted = 0 
            ORDER BY c.created_at DESC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching all complaints: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Gets complaint data for PDF report generation.
 * Reuses getComplaintByID.
 * @param int $complaint_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getComplaintReportData($complaint_id)
{
    return getComplaintByID($complaint_id);
}


// ===================================
// Staff Functions
// ===================================

/**
 * Gets all non-deleted staff members (excluding the root 'admin' user).
 * Includes department name via JOIN.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllStaff()
{
    include "connection.php";
    $sql = "SELECT st.*, d.department_name 
            FROM staff st
            LEFT JOIN department d ON st.department_id = d.department_id
            WHERE st.is_deleted = 0 AND st.email != 'admin' 
            ORDER BY st.name ASC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching staff: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Gets a specific staff member by their ID.
 * Includes department name via JOIN.
 * @param int $staff_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getStaffById($staff_id)
{
    include "connection.php";
    $sql = "SELECT st.*, d.department_name 
            FROM staff st
            LEFT JOIN department d ON st.department_id = d.department_id
            WHERE st.is_deleted = 0 AND st.staff_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $staff_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getStaffById statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Gets a specific staff member by their email.
 * Includes department name via JOIN.
 * @param string $email
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getStaffByEmail($email)
{
    include "connection.php";
    $sql = "SELECT st.*, d.department_name 
            FROM staff st
            LEFT JOIN department d ON st.department_id = d.department_id
            WHERE st.is_deleted = 0 AND st.email = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getStaffByEmail statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Checks if an email exists in either the staff or student table.
 * @param string $email
 * @return int Total count (0, 1, or 2), or -1 on error.
 */
function checkStaffOrStudentByEmail($email)
{
    include "connection.php";
    $total_count = 0;

    // Check staff
    $staff_sql = "SELECT staff_id FROM staff WHERE email = ? AND is_deleted = 0";
    $staff_stmt = mysqli_prepare($con, $staff_sql);
    if ($staff_stmt) {
        mysqli_stmt_bind_param($staff_stmt, "s", $email);
        mysqli_stmt_execute($staff_stmt);
        mysqli_stmt_store_result($staff_stmt);
        $total_count += mysqli_stmt_num_rows($staff_stmt);
        mysqli_stmt_close($staff_stmt);
    } else {
        error_log("Error preparing staff email check: " . mysqli_error($con));
        return -1;
    }

    // Check student
    $student_sql = "SELECT student_id FROM student WHERE email = ? AND is_deleted = 0";
    $student_stmt = mysqli_prepare($con, $student_sql);
    if ($student_stmt) {
        mysqli_stmt_bind_param($student_stmt, "s", $email);
        mysqli_stmt_execute($student_stmt);
        mysqli_stmt_store_result($student_stmt);
        $total_count += mysqli_stmt_num_rows($student_stmt);
        mysqli_stmt_close($student_stmt);
    } else {
        error_log("Error preparing student email check: " . mysqli_error($con));
        return -1;
    }

    return $total_count;
}


// ===================================
// Student Functions
// ===================================

/**
 * Gets a specific student by their ID.
 * @param int $student_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getStudentById($student_id)
{
    include "connection.php";
    $sql = "SELECT * FROM student WHERE is_deleted = 0 AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getStudentById statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Gets all non-deleted students.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllStudents()
{
    include "connection.php";
    $sql = "SELECT * FROM student WHERE is_deleted = 0 ORDER BY name ASC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching students: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Gets all students as a PHP array.
 * @return array Array of students, or empty array on error.
 */
function getAllStudentDataArray()
{
    include "connection.php";
    $q1 = "SELECT * FROM student WHERE is_deleted = 0 ORDER BY name ASC";
    $table = mysqli_query($con, $q1);
    if ($table) {
        $columns = mysqli_fetch_all($table, MYSQLI_ASSOC);
        return $columns;
    } else {
        error_log("Error fetching student data array: " . mysqli_error($con));
        return []; // Return empty array on error
    }
}


// ===================================
// Login & Password Check Functions
// ===================================

/**
 * Checks student's current password for password change validation.
 * (Assumes plain text passwords as in original code).
 * @param array $data ['student_id', 'password']
 * @return void (echos 1 or 0)
 */
function checkStudentPassword($data)
{
    include "connection.php";
    $student_id = $data["student_id"];
    $password = $data["password"];

    $sql = "SELECT password FROM student WHERE is_deleted = 0 AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row && $password === $row['password']) {
            echo 1; // Match
        } else {
            echo 0; // No match or student not found
        }
    } else {
        error_log("Error preparing checkStudentPassword statement: " . mysqli_error($con));
        echo 0;
    }
}

/**
 * Checks if the provided email belongs to the specified student ID.
 * Used for email change validation.
 * @param array $data ['student_id', 'email']
 * @return void (echos 1 or 0)
 */
function checkCurrentStudentEmail($data)
{
    include "connection.php";
    $student_id = $data["student_id"];
    $email = $data["email"];

    $sql = "SELECT student_id FROM student WHERE is_deleted = 0 AND email = ? AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $email, $student_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        echo $count;
    } else {
        error_log("Error preparing checkCurrentStudentEmail statement: " . mysqli_error($con));
        echo 0;
    }
}

/**
 * Handles login for both staff/admin and students.
 * Sets session variables upon successful login.
 * (Assumes plain text passwords).
 * @param array $data ['email', 'password']
 * @return void (echos 'admin', 'customer', or '')
 */
function getLogin($data)
{
    include "connection.php";
    $email = $data["email"];
    $password = $data["password"];
    $value = "";

    // Check staff/admin table
    $loginStaffSql = "SELECT staff_id, password, staff_role, email FROM staff WHERE email = ? AND is_deleted = 0";
    $staffStmt = mysqli_prepare($con, $loginStaffSql);
    if ($staffStmt) {
        mysqli_stmt_bind_param($staffStmt, "s", $email);
        mysqli_stmt_execute($staffStmt);
        $staffResult = mysqli_stmt_get_result($staffStmt);
        $staffRow = mysqli_fetch_assoc($staffResult);
        mysqli_stmt_close($staffStmt);

        if ($staffRow && $password === $staffRow['password']) {
            $_SESSION["staff_id"] = $staffRow["staff_id"];
            $_SESSION["user_role"] = $staffRow["staff_role"]; // 'admin' or 'staff'
            $_SESSION["admin_email"] = $staffRow["email"]; // Store email for password change
            $value = "admin";
        }
    } else {
        error_log("Error preparing staff login statement: " . mysqli_error($con));
    }

    // If not found in staff, check student table
    if (empty($value)) {
        $loginStudentSql = "SELECT student_id, password FROM student WHERE email = ? AND is_deleted = 0";
        $studentStmt = mysqli_prepare($con, $loginStudentSql);
        if ($studentStmt) {
            mysqli_stmt_bind_param($studentStmt, "s", $email);
            mysqli_stmt_execute($studentStmt);
            $studentResult = mysqli_stmt_get_result($studentStmt);
            $studentRow = mysqli_fetch_assoc($studentResult);
            mysqli_stmt_close($studentStmt);

            if ($studentRow && $password === $studentRow['password']) {
                $_SESSION["student_id"] = $studentRow["student_id"];
                $value = "customer";
            }
        } else {
            error_log("Error preparing student login statement: " . mysqli_error($con));
        }
    }

    echo $value;
}

/**
 * Checks staff's current password by email.
 * (Assumes plain text passwords).
 * @param array $data ['email', 'password']
 * @return void (echos 1 or 0)
 */
function checkStaffPasswordByEmail($data)
{
    include "connection.php";
    $email = $data["email"];
    $password = $data["password"];

    $sql = "SELECT password FROM staff WHERE password = ? AND email = ? AND is_deleted = 0";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $password, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        echo $count;
    } else {
        error_log("Error preparing checkStaffPasswordByEmail statement: " . mysqli_error($con));
        echo 0;
    }
}

/**
 * Checks if an email exists in EITHER student or staff table.
 * Used for email change validation.
 * @param array $data ['email_to_check']
 * @return void (echos JSON {"exists": true/false})
 */
function checkEmailExistsAny($data)
{
    include 'connection.php';
    $email = $data['email_to_check'];
    $count = checkStaffOrStudentByEmail($email); // Reuse the combined check

    $response = array('exists' => $count > 0);
    echo json_encode($response);
    exit;
}


// ===================================
// Feedback Functions
// ===================================

/**
 * Gets all feedback entries.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllFeedback()
{
    include "connection.php";
    $sql = "SELECT * FROM feedback ORDER BY date_updated DESC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching feedback: " . mysqli_error($con));
        return false;
    }
    return $result;
}


// ===================================
// Dashboard Count Functions
// ===================================

/**
 * Counts non-deleted records in a given table.
 * @param string $table The name of the table.
 * @return void (echos count)
 */
function dataCount($table)
{
    include "connection.php";
    $table = mysqli_real_escape_string($con, $table);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table name for dataCount: " . $table);
        echo 0;
        return;
    }

    $sql = "SELECT COUNT(*) as count FROM `$table` WHERE is_deleted = 0";
    $res = mysqli_query($con, $sql);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        echo $row['count'];
    } else {
        error_log("Error counting data for table $table: " . mysqli_error($con));
        echo 0;
    }
}

/**
 * Counts non-deleted records with a specific WHERE clause.
 * WARNING: $where is not sanitized. Construct it safely.
 * @param string $table The name of the table.
 * @param string $where The SQL WHERE clause (e.g., "complaint_status = 1").
 * @return void (echos count)
 */
function dataCountWhere($table, $where)
{
    include "connection.php";
    $table = mysqli_real_escape_string($con, $table);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table name for dataCountWhere: " . $table);
        echo 0;
        return;
    }

    // $where is used directly. Ensure it's safe.
    $sql = "SELECT COUNT(*) as count FROM `$table` WHERE $where AND is_deleted = 0";
    $res = mysqli_query($con, $sql);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        echo $row['count'];
    } else {
        error_log("Error counting data with WHERE for table $table: " . mysqli_error($con));
        echo 0;
    }
}


// ===================================
// Settings Functions
// ===================================

/**
 * Gets all settings (assumes one row).
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllSettings()
{
    include "connection.php";
    $sql = "SELECT * FROM settings LIMIT 1";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching settings: " . mysqli_error($con));
        return false;
    }
    return $result;
}

?>