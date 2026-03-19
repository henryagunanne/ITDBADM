<?php
session_start();
include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $query = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?msg=deleted&tab=user-management');
        exit;
    } else {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?msg=failed&tab=user-management');
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    $conn->close();
    exit();
}
?>