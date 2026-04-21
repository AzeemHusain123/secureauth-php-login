<?php
$host = "localhost";
$user = "root";
$pass = "root1234";
$dbname = "secure_login";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
