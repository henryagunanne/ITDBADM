<?php
session_start();
require_once '../config/db-connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    echo json_encode(['success' => false]);
    exit;
}

$payment_id = (int)$_POST['payment_id'];
$status     = $_POST['status'];

$allowed = ['PENDING', 'PAID', 'FAILED'];
if (!in_array($status, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

$stmt = mysqli_prepare($conn, "UPDATE sale_payment SET payment_status = ? WHERE payment_id = ?");
mysqli_stmt_bind_param($stmt, 'si', $status, $payment_id);
$result = mysqli_stmt_execute($stmt);

echo json_encode(['success' => $result]);
?>