<?php
session_start();
require_once '../config/db-connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$sale_id = (int)($_POST['sale_id'] ?? 0);
if (!$sale_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid sale ID']);
    exit;
}

try {
    $conn->begin_transaction();

    // get store_id and sale items before deleting
    $sale_stmt = $conn->prepare("SELECT store_id FROM sale WHERE sale_id = ?");
    $sale_stmt->bind_param('i', $sale_id);
    $sale_stmt->execute();
    $sale_row = $sale_stmt->get_result()->fetch_assoc();
    $sale_stmt->close();

    if (!$sale_row) throw new Exception("Sale not found");
    $store_id = $sale_row['store_id'];

    // get sale items
    $items_stmt = $conn->prepare("SELECT bean_id, quantity FROM sale_items WHERE sale_id = ?");
    $items_stmt->bind_param('i', $sale_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    $items = [];
    while ($row = $items_result->fetch_assoc()) {
        $items[] = $row;
    }
    $items_stmt->close();

    // restore inventory
    foreach ($items as $item) {
        $inv_stmt = $conn->prepare("UPDATE store_inventory 
                                    SET quantity_kg = quantity_kg + ? 
                                    WHERE bean_id = ? AND store_id = ?");
        $inv_stmt->bind_param('iii', $item['quantity'], $item['bean_id'], $store_id);
        if (!$inv_stmt->execute()) throw new Exception("Failed to restore inventory");
        $inv_stmt->close();
    }

    // delete sale payment
    $stmt = $conn->prepare("DELETE FROM sale_payment WHERE sale_id = ?");
    $stmt->bind_param('i', $sale_id);
    $stmt->execute();
    $stmt->close();

    // delete sale items
    $stmt = $conn->prepare("DELETE FROM sale_items WHERE sale_id = ?");
    $stmt->bind_param('i', $sale_id);
    $stmt->execute();
    $stmt->close();

    // delete sale
    $stmt = $conn->prepare("DELETE FROM sale WHERE sale_id = ?");
    $stmt->bind_param('i', $sale_id);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
?>