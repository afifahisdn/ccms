<?php
/*
* get.php
*
* Contains all functions for RETRIEVING data from the CCMS database.
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
        return -1;
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
// Category Functions (NEW)
// ===================================

/**
 * Gets all non-deleted complaint categories from the 'categories' table.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllCategories()
{
    include "connection.php";
    // Now respects the is_deleted flag
    $sql = "SELECT * FROM categories WHERE is_deleted = 0 ORDER BY category_name ASC";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        error_log("Error fetching categories: " . mysqli_error($con));
        return false;
    }
    return $result;
}

/**
 * Checks if a category name already exists (case-insensitive).
 * @param string $category_name
 * @return int Count (0 or 1), or -1 on error.
 */
function checkCategoryByName($category_name)
{
    include "connection.php";
    $sql = "SELECT category_id FROM categories WHERE LOWER(category_name) = LOWER(?) AND is_deleted = 0";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $category_name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $count = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);
        return $count;
    } else {
        error_log("Error preparing checkCategoryByName statement: " . mysqli_error($con));
        return -1; // Indicate error
    }
}


// ===================================
// Complaint Functions (UPDATED)
// ===================================

/**
 * Gets all complaints submitted by a specific student.
 * Joins with dormitory and categories tables.
 * @param string $student_id (VARCHAR)
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllComplaintsByStudentID($student_id)
{
    include "connection.php";
    $sql = "SELECT c.*, d.dormitory_name, cat.category_name 
            FROM complaint c
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id
            LEFT JOIN categories cat ON c.category_id = cat.category_id
            WHERE c.is_deleted = 0 AND c.student_id = ? 
            ORDER BY c.created_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $student_id); // 's' for VARCHAR student_id
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
 * Gets a specific complaint by its ID.
 * Joins with all related tables.
 * @param int $complaint_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getComplaintByID($complaint_id)
{
    include "connection.php";
    $sql = "SELECT c.*, d.dormitory_name, s.name as student_name, s.email as student_email, 
                   s.phone as student_phone, s.student_id, st.name as staff_name,
                   cat.category_name
            FROM complaint c
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id
            LEFT JOIN student s ON c.student_id = s.student_id
            LEFT JOIN staff st ON c.assigned_staff_id = st.staff_id 
            LEFT JOIN categories cat ON c.category_id = cat.category_id
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
 * Gets all non-deleted complaints for the admin/staff view, with filtering.
 * @param array $filters (e.g., ['status' => 'Open', 'dorm_id' => 1, 'search_query' => 'leak'])
 * @param string $role ('admin' or 'staff')
 * @param int $staff_id (The ID of the logged-in staff member)
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getFilteredComplaints($filters = [], $role = 'admin', $staff_id = 0)
{
    include "connection.php";
    
    $sql = "SELECT c.*, s.name as student_name, d.dormitory_name, cat.category_name,
                   st.name AS staff_name 
            FROM complaint c 
            LEFT JOIN student s ON c.student_id = s.student_id 
            LEFT JOIN dormitory d ON c.dormitory_id = d.dormitory_id 
            LEFT JOIN categories cat ON c.category_id = cat.category_id
            LEFT JOIN staff st ON c.assigned_staff_id = st.staff_id
            WHERE c.is_deleted = 0";
    
    $params = [];
    $types = "";

    // --- Role-Based Filtering for Staff ---
    if ($role == 'staff' && $staff_id > 0) {
        // 1. Get the staff member's department ID
        $staff_dept_id = 0;
        $staff_result = getStaffById($staff_id);
        if ($staff_result && $staff_data = mysqli_fetch_assoc($staff_result)) {
            $staff_dept_id = (int)$staff_data['department_id'];
        }

        if ($staff_dept_id > 0) {
            // 2. Add SQL to filter by their department OR by their ID (for assigned)
            $sql .= " AND ( (cat.department_id = ?) OR (c.assigned_staff_id = ?) )";
            $params[] = $staff_dept_id;
            $params[] = $staff_id;
            $types .= "ii";
        } else {
            // Failsafe: Staff member has no department, only show complaints assigned to them
            $sql .= " AND c.assigned_staff_id = ?";
            $params[] = $staff_id;
            $types .= "i";
        }
    }
    // Admin sees all (no extra WHERE clause)

    // --- General Search Query ---
    if (!empty($filters['search_query'])) {
        $sql .= " AND (c.complaint_id = ? OR c.complaint_title LIKE ? OR s.name LIKE ? OR c.room_number LIKE ?)";
        $search_term = "%" . $filters['search_query'] . "%";
        $params[] = $filters['search_query']; // For exact ID match
        $params[] = $search_term; // For title
        $params[] = $search_term; // For student name
        $params[] = $search_term; // For room number
        $types .= "ssss";
    }

    // --- Specific Filters ---
    if (!empty($filters['status'])) {
        $sql .= " AND c.complaint_status = ?";
        $params[] = $filters['status'];
        $types .= "s";
    }
    if (!empty($filters['dorm_id'])) {
        $sql .= " AND c.dormitory_id = ?";
        $params[] = $filters['dorm_id'];
        $types .= "i";
    }
    if (!empty($filters['cat_id'])) {
        $sql .= " AND c.category_id = ?";
        $params[] = $filters['cat_id'];
        $types .= "i";
    }

    // Default sorting
    $sql .= " ORDER BY c.created_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } else {
        error_log("Error preparing getFilteredComplaints statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Gets complaint data for PDF report generation.
 * @param int $complaint_id
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getComplaintReportData($complaint_id)
{
    // Reuses the most detailed query
    return getComplaintByID($complaint_id);
}


// ===================================
// Staff Functions
// ===================================

/**
 * Gets all non-deleted staff members (excluding root 'admin').
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
 * @return int Total count (0 or 1), or -1 on error.
 */
function checkStaffOrStudentByEmail($email)
{
    include "connection.php";
    $total_count = 0;

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
 * @param string $student_id (VARCHAR)
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getStudentById($student_id)
{
    include "connection.php";
    $sql = "SELECT * FROM student WHERE is_deleted = 0 AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $student_id); // 's' for VARCHAR student_id
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
        return [];
    }
}


// ===================================
// Login & Password Check Functions
// ===================================

/**
 * Checks student's current password. Echos 1 (match) or 0 (no match).
 * @param array $data ['student_id', 'password']
 */
function checkStudentPassword($data)
{
    include "connection.php";
    $student_id = $data["student_id"]; // This is now VARCHAR
    $password = $data["password"];

    $sql = "SELECT password FROM student WHERE is_deleted = 0 AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $student_id); // 's' for VARCHAR
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row && $password === $row['password']) {
            echo 1; // Match
        } else {
            echo 0; // No match
        }
    } else {
        error_log("Error preparing checkStudentPassword statement: " . mysqli_error($con));
        echo 0;
    }
}

/**
 * Checks if the provided email belongs to the specified student ID. Echos 1 or 0.
 * @param array $data ['student_id', 'email']
 */
function checkCurrentStudentEmail($data)
{
    include "connection.php";
    $student_id = $data["student_id"]; // VARCHAR
    $email = $data["email"];

    $sql = "SELECT student_id FROM student WHERE is_deleted = 0 AND email = ? AND student_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $email, $student_id); // 's' for VARCHAR student_id
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
 * Handles login for both staff/admin and students. Echos 'admin', 'customer', or ''.
 * @param array $data ['email', 'password']
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
            $_SESSION["user_role"] = $staffRow["staff_role"];
            $_SESSION["admin_email"] = $staffRow["email"];
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
                $_SESSION["student_id"] = $studentRow["student_id"]; // Now stores the VARCHAR student_id
                $value = "customer";
            }
        } else {
            error_log("Error preparing student login statement: " . mysqli_error($con));
        }
    }
    echo $value;
}

/**
 * Checks staff's current password by email. Echos 1 or 0.
 * @param array $data ['email', 'password']
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
 * Checks if an email exists in EITHER student or staff table. Echos JSON.
 * @param array $data ['email_to_check']
 */
function checkEmailExistsAny($data)
{
    include 'connection.php';
    $email = $data['email_to_check'];
    $count = checkStaffOrStudentByEmail($email);
    $response = array('exists' => $count > 0);
    echo json_encode($response);
    exit;
}


// ===================================
// Feedback Functions
// ===================================

/**
 * Gets all feedback entries, joining student info.
 * @return mysqli_result|false The result set on success, false on failure.
 */
function getAllFeedback()
{
    include "connection.php";
    // SQL updated to JOIN student table and check is_deleted
    $sql = "SELECT f.*, s.name, s.email 
            FROM feedback f
            LEFT JOIN student s ON f.student_id = s.student_id
            WHERE f.is_deleted = 0
            ORDER BY f.date_updated DESC";
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
 * Counts non-deleted records in a given table. Echos count.
 * @param string $table The name of the table.
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
 * Counts non-deleted records with a specific WHERE clause. Echos count.
 * @param string $table The name of the table.
 * @param string $where The SQL WHERE clause.
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