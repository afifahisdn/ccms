<?php
// Include database connection
include "connection.php";

// Function to update a field in a table
function updateDataTable($data)
{
    include 'connection.php';

    $id_fild = $data['id_fild'];
    $id = $data['id'];
    $field = $data['field'];
    $value = $data['value'];
    $table = $data['table'];

    $sql = "UPDATE $table SET $field = '$value' where $id_fild = '$id'";
    return mysqli_query($con, $sql);
}

// Function to update an image field in a table
function editImages($data, $img)
{
    global $con;

    $id_field = $data["id_field"];
    $id = $data["id"];
    $field = $data["field"];
    $table = $data["table"];
    $img = mysqli_real_escape_string($con, $img); // Prevent SQL injection

    $sql = "UPDATE $table SET $field = '$img' WHERE $id_field = '$id'";
    return mysqli_query($con, $sql);
}

// Function to update a setting in the settings table
function changePageSettings($data)
{
    global $con;

    $field = $data["field"];
    $value = mysqli_real_escape_string($con, $data["value"]); // Prevent SQL injection

    $sql = "UPDATE settings SET $field = '$value'";
    return mysqli_query($con, $sql);
}

// Function to update an image setting in the settings table
function editSettingImage($data, $img)
{
    global $con;

    $field = $data["field"];
    $img = mysqli_real_escape_string($con, $img); // Prevent SQL injection

    $sql = "UPDATE settings SET $field = '$img'";
    return mysqli_query($con, $sql);
}

// Function to update tracking status in the request table
function updateTrackingStatus($data)
{
    global $con;

    $tracking_id = $data['tracking_id'];
    $tracking_status = mysqli_real_escape_string($con, $data['tracking_status']); // Prevent SQL injection

    $sql = "UPDATE request SET tracking_status = '$tracking_status', date_updated = NOW() WHERE tracking_id = '$tracking_id'";
    if (mysqli_query($con, $sql)) {
        return 'success';
    } else {
        return 'error';
    }
}

// Function to update customer profile details
function updateProfile($data) {
    global $con;

    $customer_id = $data['customer_id'];
    $name = $data['new_name'];
    $phone = $data['new_phone'];
    $address = $data['new_address'];
    $gender = $data['new_gender'];

    $stmt = $con->prepare("UPDATE customer SET name = ?, phone = ?, address = ?, gender = ? WHERE customer_id = ?");
    $stmt->bind_param("sssii", $name, $phone, $address, $gender, $customer_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
