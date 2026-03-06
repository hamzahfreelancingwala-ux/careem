<?php
$host = 'localhost';
$db   = 'rsoa_rsoa00112_28';
$user = 'rsoa_rsoa00112_28';
$pass = '654321#';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
