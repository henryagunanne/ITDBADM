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
                $stmt1->close();
                $stmt2->close();
            } else {
                echo json_encode(['message' => 'Error inserting customer']);
                $stmt1->close();
                $stmt2->close();
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
        $currency = $_POST['currency_code'];
        $payment_method = $_POST['payment_method'];


        try {
            // Start transaction
            $conn->begin_transaction();

            // Add sale (total amount is added later)
            $sql = "INSERT INTO sale 
                    (store_id, customer_id, sale_date)
                    VALUES (?, ?, ?)";
            
            $stmt3 = $conn->prepare($sql);
            if (!$stmt3) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt3->bind_param('iis', $store, $customer_id, $sale_date);
            if (!$stmt3->execute()) {
                throw new Exception("Execute failed: " . $stmt3->error);
            }

            $sale_id = $conn->insert_id;
            $stmt3->close();

            // Create sale items
            foreach ($cartItems as $bean_id => $item) {
                $quantity = $item['quantity'];

                // Get price from database
                $sql = 'SELECT price_per_kg FROM coffee_bean WHERE bean_id = ?';
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param('i', $bean_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $unit_price = $row['price_per_kg'];

                $subtotal = $unit_price * $quantity;

                $total += $subtotal;

                // add sale item
                $sql = 'INSERT INTO sale_items
                        (sale_id, bean_id, quantity, unit_price, subtotal)
                        VALUES (?, ?, ?, ?, ?)';

                $stmt1 = $conn->prepare($sql);

                if (!$stmt1) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt1->bind_param('iiidd', $sale_id, $bean_id, $quantity, $unit_price, $subtotal);

                if (!$stmt->execute()) {
                    $stmt->close();
                    $stmt1->close();
                    throw new Exception("Execute failed: " . $stmt1->error);
                }

                $stmt->close();
                $stmt1->close();

                // Update inventory
                $sql = 'UPDATE store_inventory SET quantity_kg = quantity_kg - ? WHERE bean_id = ? AND store_id = ?';
                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param('iii', $quantity, $bean_id, $store_id);
                if(!$stmt->execute()){
                    $stmt->close();
                    throw new Exception("Execute failed: " . $stmt->error);
                }

                $stmt->close();
            }

            // Update sale to add total_amount
            $sql = 'UPDATE sale SET total_amount = ? WHERE sale_id = ?';
            $saleStmt = $conn->prepare($sql);

            if (!$saleStmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $saleStmt->bind_param('di', $total, $sale_id);
            if(!$saleStmt->execute()){
                $saleStmt->close();
                throw new Exception("Execute failed: " . $saleStmt->error);
            }
            $saleStmt->close();

            // Add sale payment record
            $sql = 'INSERT INTO sale_payment (sale_id, payment_date, amount_paid, currency_code, payment_method)
                                VALUES (?, ?, ?, ?, ?)';
            
            $paymentStmt = $conn->prepare($sql);
            if (!$paymentStmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $paymentStmt->bind_param('isdss', $sale_id, $sale_date, $total, $currency, $payment_method);
            if(!$paymentStmt->execute()){
                $paymentStmt->close();
                throw new Exception("Execute failed: " . $paymentStmt->error);
            }
            $paymentStmt->close();


            // commit transation
            $conn->commit();

            // Clear cart 
            unset($_SESSION['cart']);

            // send confirmation message
            echo json_encode(['message' => 'Sale Processed Successfully']);
            
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            echo json_encode(['type' => 'danger', 'message' => 'Order failed: ' . $e->getMessage()]);
            // header("Location: ../pages/checkout.php");
            exit();
        } finally {
            $conn->close();
        }
    }
?>