<?php

function addBranch($data)
{
    include "connection.php";

    $branch_name = $data["branch_name"];
    $count = checkBranchByName($branch_name);

    if ($count == 0) {
        $sql = "INSERT INTO branch(branch_name, is_deleted) VALUES('$branch_name', 0)";
        return mysqli_query($con, $sql);
    } else {
        echo json_encode($count);
    }
}

function addArea($data)
{
    include "connection.php";

    $area_name = $data["area_name"];

    $count = checkAreaByName($area_name);

    if ($count == 0) {
        $sql = "INSERT INTO area(area_name, is_deleted) VALUES('$area_name', 0)";
        return mysqli_query($con, $sql);
    } else {
        echo json_encode($count);
    }
}

function addPrice($data)
{
    include "connection.php";

    $start_area = $data["start_area"];
    $end_area = $data["end_area"];
    $price = $data["price"];

    $count = checkPrice($start_area, $end_area);

    if ($count == 0) {
        $sql = "INSERT INTO price_table(start_area, end_area, price ,is_deleted, date_updated) VALUES('$start_area', '$end_area', '$price', 0 , now())";
        return mysqli_query($con, $sql);
    } else {
        echo json_encode($count);
    }
}

function addRequest($data)
{
    include "connection.php";

    $customer_id = $data["customer_id"];
    $sender_name = $data["sender_name"];
    $sender_phone = $data["sender_phone"];
    $sender_address = $data["sender_address"];
    $weight = $data["weight"];
    $send_location = $data["send_location"];
    $end_location = $data["end_location"];
    $total_fee = $data["total_fee"];
    $rec_phone = $data["rec_phone"];
    $rec_address = $data["rec_address"];
    $rec_name = $data["rec_name"];

    $sql = "INSERT INTO request(customer_id, sender_name, sender_phone, sender_address, weight, send_location, end_location, total_fee, rec_phone, rec_address, is_deleted, date_updated, tracking_status, rec_name) 
	VALUES('$customer_id', '$sender_name', '$sender_phone', '$sender_address', '$weight', '$send_location', '$end_location', '$total_fee', '$rec_phone', '$rec_address', 0 , now(), 1 , '$rec_name')";
    return mysqli_query($con, $sql);
}

function addRequestAdmin($data) {
    include 'connection.php';

    // Prepare data
    $customer_id = $data['customer_id'];
    $sender_name = $data["sender_name"];
    $sender_address = $data['sender_address'];
    $sender_phone = $data['sender_phone'];
    $weight = $data['weight'];
    $send_location = $data['send_location'];
    $end_location = $data['end_location'];
    $total_fee = $data['total_fee'];
    $rec_name = $data['rec_name'];
    $rec_phone = $data['rec_phone'];
    $rec_address = $data['rec_address'];

    // Insert into database
    $sql = "INSERT INTO request(customer_id, sender_name, sender_phone, sender_address, weight, send_location, end_location, total_fee, rec_phone, rec_address, is_deleted, date_updated, tracking_status, rec_name) 
            VALUES('$customer_id', '$sender_name', '$sender_phone', '$sender_address', '$weight', '$send_location', '$end_location', '$total_fee', '$rec_phone', '$rec_address', 0, now(), 1, '$rec_name')";

    return mysqli_query($con, $sql);
}

function addEmployee($data)
{
    include "connection.php";

    $name = $data["name"];
    $email = $data["email"];
    $phone = $data["phone"];
    $address = $data["address"];
    $gender = $data["gender"];
    $password = $data["password"];
    $branch_id = $data["branch_id"];

    $count = checkemployeetByEmail($email);

    if ($count == 0) {
        $sql = "INSERT INTO employee(name, email, phone, address, gender, password ,is_deleted, branch_id) VALUES('$name', '$email', '$phone', '$address', '$gender', '$password', 0 , '$branch_id')";
        return mysqli_query($con, $sql);
    } else {
        echo json_encode($count);
    }
}

//contact
function addMessage($data)
{
    include "connection.php";

    $name = $data["name"];
    $email = $data["email"];
    $subject = $data["subject"];
    $message = $data["message"];

    $sql = "INSERT INTO contact(name, email, subject, message, date_updated) VALUES('$name', '$email', '$subject', '$message', now())";
    return mysqli_query($con, $sql);
}

function createCustomer($data) {
    include 'connection.php';

    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];
    $gender = $data['gender'];
    $password = $data['password'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        // If the email already exists, return an error
        echo "Email already exists";
        return;
    } else {
        // If the email does not exist, insert the new customer into the database
        $sql = "INSERT INTO customer(name, email, phone, address, gender, password, is_deleted) VALUES('$name', '$email', '$phone', '$address', '$gender', '$password', 0 )";
        if (mysqli_query($con, $sql)) {
            echo "Customer created successfully";
        } else {
            echo "Error creating customer";
        }
    }
}