<?php
session_start();
include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $supplier_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM supplier WHERE supplier_id = ?");
    $stmt->bind_param("i", $supplier_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=supplier-management');
        exit;
    } else {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=supplier-management&msg=failed');
        exit;
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
    $conn->close();
    exit();
}
?>