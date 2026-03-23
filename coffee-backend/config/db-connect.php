<?php
    // Database credentials
    $host = "10.2.0.17"; // VM IP
    $port = 22017;
    $database = "cool_beans";
    $username = "student1";
    $password = "Dlsu1234!";

    $conn = new mysqli($host, $username, $password, $database, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>