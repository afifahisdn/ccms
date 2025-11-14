<?php
/*
* delete.php
*
* Contains functions for deleting records.
* deleteDataTables() = Soft delete (sets is_deleted=1)
* permanantDeleteDataTable() = Hard delete (PERMANENTLY removes row)
*/

// Use include_once for safety
include_once "connection.php";

/**
 * Soft deletes a record by setting is_deleted = 1.
 * Expects data from a form post ($_POST).
 *
 * @param array $data Asssociative array containing 'id_fild', 'id', 'table'.
 * @return bool True on success, false on failure.
 */
function deleteDataTables($data)
{
    include "connection.php"; // Ensure $con is available

    // Basic sanitization
    $id_fild = mysqli_real_escape_string($con, $data["id_fild"]);
    $id = mysqli_real_escape_string($con, $data["id"]);
    $table = mysqli_real_escape_string($con, $data["table"]);

    // Prevent deleting critical tables or fields
    if ($table == 'staff' && $id == '1') { // Protect main admin
        error_log("Attempted to soft-delete main admin (ID 1).");
        return false;
    }

    // Ensure id_fild and table are valid identifiers (basic check)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $id_fild) || !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table or field name for deletion: table=$table, field=$id_fild");
        return false;
    }

    $sql = "UPDATE `$table` SET is_deleted = '1' WHERE `$id_fild` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        // Bind ID as a string ('s'), this works for both INT and VARCHAR PKs
        mysqli_stmt_bind_param($stmt, "s", $id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            error_log("Error soft deleting record: " . mysqli_stmt_error($stmt));
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        error_log("Error preparing soft delete statement: " . mysqli_error($con));
        return false;
    }
}

/**
 * Permanently deletes a record from the database. USE WITH CAUTION.
 * Expects JSON data from php://input.
 *
 * @param string $jsonData JSON string containing 'id_fild', 'id', 'table'.
 * @return void Echos JSON response.
 */
function permanantDeleteDataTable($jsonData)
{
    include "connection.php";

    $data = json_decode($jsonData, true);

    if (!$data || !isset($data["id_fild"]) || !isset($data["id"]) || !isset($data["table"])) {
        error_log("Invalid data received for permanent delete: " . $jsonData);
        echo json_encode(["success" => false, "error" => "Invalid input data"]);
        return;
    }

    // Basic sanitization
    $id_fild = mysqli_real_escape_string($con, $data["id_fild"]);
    $id = mysqli_real_escape_string($con, $data["id"]);
    $table = mysqli_real_escape_string($con, $data["table"]);

    // Prevent deleting critical tables or fields
    if ($table == 'staff' && $id == '1') {
        echo json_encode(["success" => false, "error" => "Cannot permanently delete main administrator."]);
        return;
    }
    if ($table == 'student' && $id_fild == 'student_id') {
         // You might want to prevent this entirely and only allow soft-delete
         error_log("Attempted to permanent-delete a student: $id");
         // echo json_encode(["success" => false, "error" => "Students can only be soft-deleted."]);
         // return;
    }


    // Ensure id_fild and table are valid identifiers (basic check)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $id_fild) || !preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table or field name for permanent deletion: table=$table, field=$id_fild");
        echo json_encode(["success" => false, "error" => "Invalid table or field name"]);
        return;
    }

    $sql = "DELETE FROM `$table` WHERE `$id_fild` = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        // Bind ID as a string ('s'), works for both INT and VARCHAR PKs
        mysqli_stmt_bind_param($stmt, "s", $id);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => true]);
        } else {
            $error_message = mysqli_stmt_error($stmt);
            error_log("Error permanently deleting record: " . $error_message);
            echo json_encode(["success" => false, "error" => $error_message]);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_message = mysqli_error($con);
        error_log("Error preparing permanent delete statement: " . $error_message);
        echo json_encode(["success" => false, "error" => $error_message]);
    }
}

?>