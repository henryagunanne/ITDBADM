<?php
session_start();

$action  = $_POST['action'] ?? '';
$bean_id = (int)($_POST['bean_id'] ?? 0);
$qty     = (int)($_POST['qty'] ?? 1);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        require_once '../config/db-connect.php';
        // use prepared statement for safety
        $stmt = mysqli_prepare($conn, "SELECT * FROM coffee_bean WHERE bean_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $bean_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $bean = mysqli_fetch_assoc($result);

        if ($bean) {
            if (isset($_SESSION['cart'][$bean_id])) {
                $_SESSION['cart'][$bean_id]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$bean_id] = [
                    'bean'     => $bean,
                    'quantity' => $qty
                ];
            }
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$bean_id]);
        break;

    case 'update':
        if (isset($_SESSION['cart'][$bean_id])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$bean_id]);
            } else {
                $_SESSION['cart'][$bean_id]['quantity'] = $qty;
            }
        }
        break;
        
    case 'get':
        // just return current cart
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        exit;
}

// recalculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['bean']['price_per_kg'] * $item['quantity'];
}

echo json_encode([
    'success'    => true,
    'cart_count' => count($_SESSION['cart']),
    'total'      => number_format($total, 2),
    'cart'       => array_values($_SESSION['cart'])
]);
?>