<?php
$host = "localhost";
$db = "signup_page";
$user = "root";
$pass = ""; // your MySQL password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
