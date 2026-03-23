<?php
    // Database credentials
    $host = "10.2.0.17"; // VM IP
    $port = 22017;
    $database = "cool_beans";
    $username = "root";
    $password = "RqxWHOg1ZJfY";

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>