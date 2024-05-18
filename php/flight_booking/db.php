<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "flight_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
