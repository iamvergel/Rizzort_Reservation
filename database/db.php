<?php
$server_name = "localhost";
$db_username = "root";
$db_password = "";

$conn = new mysqli($server_name, $db_username, $db_password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$databaseFile = file_get_contents('\xampp\htdocs\Rizzort_Reservation\database\db.sql');

if ($conn->multi_query($databaseFile) === true) {
}
?>
