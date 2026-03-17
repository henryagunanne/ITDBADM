<?php
    session_start();
    include("../config/db-connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get customer Details from order form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];
        $city = $_POST['city_id'];

        // Validate required fields
        if (empty($first_name) || empty($email) || empty($city)) {
            echo json_encode(['message' => 'Missing required Fields']);
            exit;
        }

        // Check if customer is already in database
        $query = "SELECT customer_id FROM customer WHERE email = ?";
        $stmt1 = $conn->prepare($query);
        $stmt1->bind_param("s", $email);
        $stmt1->execute();

        $result = $stmt1->get_result();
        $customer_id = null;

        if ($result->num_rows > 0) {
            // Customer exists
            $row = $result->fetch_assoc();
            $customer_id = $row['customer_id'];
        } else {
            // Customer does NOT exist -> INSERT
        
            $insertQuery = "INSERT INTO customer 
                            (first_name, last_name, email, contact_number, address, city_id)
                            VALUES (?, ?, ?, ?, ?, ?)";
        
            $stmt2 = $conn->prepare($insertQuery);
            $stmt2->bind_param("sssssi", 
                $first_name,
                $last_name,
                $email,
                $contact_number,
                $address,
                $city_id
            );
        
            if ($stmt2->execute()) {
                $customer_id = $stmt2->insert_id;
            } else {
                echo json_encode(['message' => 'Error inserting customer']);
                exit;
            }
        }

        
        // Get Cart Items
        if (!empty($_SESSION['cart'])) {
            $cartItems = $_SESSION['cart'];
        }


        // Get sale details from order form
        $store = $_POST['store_id'];
        $sale_date = date("Y-m-d");
        $totalAmount = $_POST['total_amount'];
        $currency = $_POST['currency_code'];
        // $bean_id = $_POST['bean_id'];
        $quantity = $_POST['quantity'];
        $unit_price = $_POST['unit_price'];
        $subtotal = $_POST['subtotal'];


        try {
            // Start transaction
            $conn->begin_transaction();

            // Add sale
            $sql = "INSERT INTO sale 
                    (store_id, customer_id, sale_date, total_amount, currency_code)
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt3 = $conn->prepare($sql);
            if (!$stmt3) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt3->bind_param('iisds', $store, $customer_id, $sale_date, $totalAmount, $currency);
            if (!$stmt3->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $sale_id = $conn->insert_id;
            $stmt3->close();

            // Create sale items
            foreach ($cartItems as $bean_id => $item) {
                
            }


            $conn->commit();
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            $_SESSION['sale_feedback'] = ['type' => 'danger', 'message' => 'Order failed: ' . $e->getMessage()];
            // header("Location: ../pages/checkout.php");
            exit();
        } finally {
            $conn->close();
        }
    }
?>