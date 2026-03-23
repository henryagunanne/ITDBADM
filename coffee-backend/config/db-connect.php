<?php
    // Database credentials
    $host = "localhost"; // VM IP
    $port = 3307;
    $database = "cool_beans";
    $username = "student1";
    $password = "Dlsu1234!";

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>