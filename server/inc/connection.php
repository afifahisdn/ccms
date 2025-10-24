<?php #connection.php
# build connection between database and system
$con = mysqli_connect("localhost", "root", "", "vexpress");

# testing if connection succesfully
if (!$con) {
    die("Connection failed");
}
?>
