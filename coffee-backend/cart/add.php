<?php
    session_start();

    $bean_id = $_POST['bean_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // sanitize quantity
    $quantity = max(1, (int)$quantity);

    // add or update item
    if (isset($_SESSION['cart'][$bean_id])) {
        $_SESSION['cart'][$bean_id] += $quantity;
    } else {
        $_SESSION['cart'][$bean_id] = $quantity;
    }

    echo json_encode(['message' => "Item added to cart"]);
?>