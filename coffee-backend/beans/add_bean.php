<?php
    session_start(); 

    include("config/db-connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bean_id = $_POST['bean_id'];
        $bean_name = $_POST['bean_name'];
        $variety = $_POST['variety'];
        $origin_province = $_POST['origin_province'];
        $roast_level = $_POST['roast_level'];
        $price_per_kg = $_POST['price_per_kg'];
        $supplier = $_POST['supplier'];
        $description = $_POST['description'] ? $_POST['description'] : null;

        // Get the province id from the province table
        $query = "INSERT INTO coffee_bean ('bean_id', 'bean_name', 'variety', 'origin_province_id', 'roast_level', 'price_per_kg', 'supplier_id', 'description')
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($query);
        $stmt1->bind_param("issisdis", $bean_id, $bean_name, $variety, $origin_province, $roast_level, $price_per_kg, $supplier, $description);
        
        if($stmt1->execute()) {
            echo json_encode(['success' => 'Coffee Bean Added Successfully']);
            $stmt1->close();
            $conn->close();
            exit;
        } else {
            echo json_encode(['error' => 'Failed to Add Coffee Bean']);
            $stmt1->close();
            $conn->close();
            exit;
        }
        
    } else {
        echo json_encode(['error' => 'Invalid request method.']);
        $conn->close();
        exit();
    }
?>