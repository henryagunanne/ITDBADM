<?php
    // Database credentials
    $host = "localhost"; // VM IP
    $port = 3306;
    $database = "cool_beans";
    $username = "student1";
    $password = "Dlsu1234!";

    // Create connection
    $conn = new mysqli($host, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>