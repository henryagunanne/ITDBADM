<?php
    session_start();
    include("../config/db-connect.php");
    
    $cart = $_SESSION['cart'] ?? [];
    
    foreach ($cart as $bean_id => $quantity) {
    
        // fetch bean details
        $query = "
                    SELECT bean_name, variety 
                    FROM coffee_bean 
                    WHERE bean_id = ?
                ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $bean_id);
        $stmt->execute();
        $bean = $stmt->fetch();
    
        echo json_encode([
            'Bean' => $bean['bean_name'],
            'Qty' => $quantity
        ]);
    }
?>