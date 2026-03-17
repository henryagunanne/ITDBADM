<?php
    session_start();

    $bean_id = $_GET['bean_id'];
    
    unset($_SESSION['cart'][$bean_id]);
    
    echo json_encode(['message' => 'Item removed']);
?>