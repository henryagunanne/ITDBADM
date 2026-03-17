<?php
    session_start();
    include("../config/db-connect.php");
    
    $cart = $_SESSION['cart'] ?? [];
    $cartItems = [];
    
    foreach ($cart as $bean_id => $quantity) {
    
        // fetch bean details
        $query = "SELECT bean_name, variety, roast_level
                  FROM coffee_bean 
                  WHERE bean_id = ?";
                  
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $bean_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $bean = $row['bean_name'];
        $variety = $row['variety'];
        $roast_level = $row['roast_level'];
    
        $cart_items[] = [
            'bean' => $bean,
            'variety' => $variety,
            'roast_level' => $roast_level,
            'qty' => $quantity
        ];
    }

    echo json_encode($cart_items);
?>