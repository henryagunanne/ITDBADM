<?php
    session_start();
    include("../config/db-connect.php");

    $query = "SELECT * FROM coffee_bean";
    $stmt = $conn->query($query);

    $beans = $stmt->fetch_assoc();

    echo json_encode(["coffee_beans" => $beans]);
    
?>