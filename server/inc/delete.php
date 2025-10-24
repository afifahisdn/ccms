<?php

function deleteDataTables($data)
{
    include "connection.php";

    $id_fild = $data["id_fild"];
    $id = $data["id"];
    $table = $data["table"];

    $sql = "UPDATE $table SET is_deleted = '1' where $id_fild='$id'";
    return mysqli_query($con, $sql);
}

function permanantDeleteDataTable($data) {
    include "connection.php";

    $data = json_decode(file_get_contents("php://input"), true);

    $id_fild = $data["id_fild"];
    $id = $data["id"];
    $table = $data["table"];

    $sql = "DELETE FROM $table WHERE $id_fild = '$id'";
    if (mysqli_query($con, $sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => mysqli_error($con)]);
    }
    mysqli_close($con);
}