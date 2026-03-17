<?php
    session_start();

    $bean_id = $_POST['bean_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity <= 0) {
        unset($_SESSION['cart'][$bean_id]);
    } else {
        $_SESSION['cart'][$bean_id] = $quantity;
    }
    
    echo json_encode(['message' => 'cart updated']);
?>