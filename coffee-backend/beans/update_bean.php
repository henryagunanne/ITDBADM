<?php
     session_start(); 

     include("../config/db-connect.php");

     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bean_id = $_POST['bean_id'];
        $bean_name = $_POST['bean_name'];
        $variety = $_POST['variety'];
        $origin_province = $_POST['origin_province_id'];
        $roast_level = $_POST['roast_level'];
        $price_per_kg = $_POST['price_per_kg'];
        $supplier = $_POST['supplier_id'];
        $description = $_POST['description'] ? $_POST['description'] : null;

        $query = "UPDATE coffee_bean 
                  SET bean_name = ?, variety = ?, origin_province_id = ? roast_level = ?, price_per_kg = ? supplier_id = ?, description = ?
                  WHERE bean_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssisdisi', $bean_name, $variety, $origin_province, $roast_level, $price_per_kg, $supplier, $description, $bean_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => `$bean_name Updated Successfully`]);
            $stmt->close();
            $conn->close();
            exit;
        } else {
            echo json_encode(['error' => 'Failed to update Coffee Bean']);
            $stmt->close();
            $conn->close();
            exit;
        }

     } else {
        echo json_encode(['error' => 'Invalid request method.']);
        $conn->close();
        exit();
     }

    
?>