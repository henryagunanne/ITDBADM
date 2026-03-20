<?php
session_start();
require_once '../config/db-connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    echo json_encode(['success' => false]);
    exit;
}

$sale_id = (int)($_GET['sale_id'] ?? 0);

// get sale info - use LEFT JOIN in case payment doesn't exist yet
$sale_stmt = $conn->prepare("
    SELECT s.*, 
           CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
           c.email, c.contact_number, c.address,
           st.store_name,
           sp.payment_method, sp.payment_status, sp.amount_paid, sp.currency_code, sp.payment_date
    FROM sale s
    JOIN customer c ON s.customer_id = c.customer_id
    JOIN store st ON s.store_id = st.store_id
    LEFT JOIN sale_payment sp ON sp.sale_id = s.sale_id
    WHERE s.sale_id = ?
");
$sale_stmt->bind_param('i', $sale_id);
$sale_stmt->execute();
$sale = $sale_stmt->get_result()->fetch_assoc();
$sale_stmt->close();

// get sale items
$items_stmt = $conn->prepare("
    SELECT si.*, cb.bean_name
    FROM sale_items si
    JOIN coffee_bean cb ON si.bean_id = cb.bean_id
    WHERE si.sale_id = ?
");
$items_stmt->bind_param('i', $sale_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = [];
while ($row = $items_result->fetch_assoc()) {
    $items[] = $row;
}
$items_stmt->close();

echo json_encode(['success' => true, 'sale' => $sale, 'items' => $items]);
?>