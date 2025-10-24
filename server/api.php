<?php
if (session_id() == "") {
    session_start();
}

include "inc/get.php";
include "inc/connection.php";
include "inc/update.php";
include "inc/delete.php";
include "inc/add.php";

if (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "getCustomerTbleData"
) {
    echo json_encode(getAllCustomer());
} elseif (isset($_GET["function_code"]) && $_GET["function_code"] == "updateData") {
    updateDataTable($_POST);
} elseif (isset($_GET["function_code"]) && $_GET["function_code"] == 'checkEmailExist') {
    checkEmailExist($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "imageUploadProducts"
) {
    $img = $_FILES["file"]["name"];
    $target_dir = "uploads/products/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = ["jpg", "jpeg", "png", "gif", "jfif", "svg", "webp"];

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $img);
        editImages($_POST, $img);
    }
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addProducts"
) {
    $img = $_FILES["file"]["name"];
    $target_dir = "uploads/products/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = ["jpg", "jpeg", "png", "gif", "jfif", "svg", "webp"];
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "deleteData"
) {
    deleteDataTables($_POST);
} elseif (isset($_GET["function_code"]) && $_GET["function_code"] == "permanantDeleteData") {
    permanantDeleteDataTable(file_get_contents("php://input"));
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "changesettings"
) {
    changePageSettings($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "SettingImage"
) {
    $img = $_FILES["file"]["name"];
    $target_dir = "uploads/settings/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = ["jpg", "jpeg", "png", "gif", "jfif", "svg", "webp"];

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir . $img);
        editSettingImage($_POST, $img);
    }
} elseif (isset($_GET["function_code"]) && $_GET["function_code"] == "login") {
    echo getLoginAdmin($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "checkPasswordByEmail"
) {
    checkPasswordByName($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addcontact"
) {
    addMessage($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addCustomer"
) {
    createCustomer($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "checkEmail"
) {
    checkUserEmail($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "checkPassword"
) {
    checkuserPassword($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addEmployee"
) {
    addEmployee($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addBranch"
) {
    addBranch($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addPrice"
) {
    addPrice($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "checkArea"
) {
    checkArea($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addArea"
) {
    addArea($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addRequest"
) {
    addRequest($_POST);
}elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "addRequestAdmin"
) {
    addRequestAdmin($_POST);
} elseif (
    isset($_GET["function_code"]) &&
    $_GET["function_code"] == "updateTrackingStatus"
) {
    echo updateTrackingStatus($_POST);
} elseif (isset($_GET["function_code"]) && $_GET["function_code"] == "updateProfile") {
    $data = json_decode(file_get_contents("php://input"), true);
    updateProfile($data);
}