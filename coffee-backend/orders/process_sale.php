<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
header('Content-Type: application/json');

include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name     = $_POST['first_name'];
    $last_name      = $_POST['last_name'];
    $email          = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $address        = $_POST['address'];
    $province_id    = (int)$_POST['city_id'];
    $store_id       = (int)$_POST['store_id'];
    $currency       = $_POST['currency_code'] ?? 'PHP';
    $payment_method = $_POST['payment_method'];

    // validate required fields
    if (empty($first_name) || empty($email) || empty($province_id) || empty($address) || empty($contact_number)) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    if (empty($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    $cartItems = $_SESSION['cart'];

    // check if customer exists
    $stmt1 = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
    $stmt1->bind_param("s", $email);
    $stmt1->execute();
    $result = $stmt1->get_result();
    $customer_id = null;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_id = $row['customer_id'];
        $stmt1->close();
    } else {
        $stmt1->close();
        $stmt2 = $conn->prepare("INSERT INTO customer 
                                 (first_name, last_name, email, contact_number, address, city_id)
                                 VALUES (?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("sssssi", $first_name, $last_name, $email, $contact_number, $address, $province_id);

        if ($stmt2->execute()) {
            $customer_id = $stmt2->insert_id;
            $stmt2->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting customer: ' . $stmt2->error]);
            $stmt2->close();
            exit;
        }
    }

    $sale_date = date("Y-m-d");
    $total     = 0;

    try {
        $conn->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
        $conn->begin_transaction();

        // insert sale
        $stmt3 = $conn->prepare("INSERT INTO sale (store_id, customer_id, sale_date) VALUES (?, ?, ?)");
        if (!$stmt3) throw new Exception("Prepare failed: " . $conn->error);
        $stmt3->bind_param('iis', $store_id, $customer_id, $sale_date);
        if (!$stmt3->execute()) throw new Exception("Execute failed: " . $stmt3->error);
        $sale_id = $conn->insert_id;
        $stmt3->close();

        // create sale items
        foreach ($cartItems as $bean_id => $item) {
            $quantity = $item['quantity'];

            // get price
            $priceStmt = $conn->prepare("SELECT price_per_kg FROM coffee_bean WHERE bean_id = ?");
            if (!$priceStmt) throw new Exception("Prepare failed: " . $conn->error);
            $priceStmt->bind_param('i', $bean_id);
            $priceStmt->execute();
            $priceResult = $priceStmt->get_result();
            $priceRow    = $priceResult->fetch_assoc();
            $unit_price  = $priceRow['price_per_kg'];
            $subtotal    = $unit_price * $quantity;
            $total      += $subtotal;
            $priceStmt->close();

            // lock row for update
            $lockStmt = $conn->prepare("
                SELECT quantity_kg 
                FROM store_inventory 
                WHERE bean_id = ? AND store_id = ?
                FOR UPDATE
            ");
            if (!$lockStmt) throw new Exception("Prepare failed: " . $conn->error);
            $lockStmt->bind_param("ii", $bean_id, $store_id);
            $lockStmt->execute();
            $result = $lockStmt->get_result();
            $row = $result->fetch_assoc();

            if (!$row) throw new Exception("Inventory not found for bean ID: $bean_id");

            $current_stock = $row['quantity_kg'];
            $lockStmt->close();

            if ($current_stock < $quantity) {
                throw new Exception("Not enough stock for bean ID: $bean_id");
            }

            // update inventory
            $invStmt = $conn->prepare("UPDATE store_inventory 
                                       SET quantity_kg = quantity_kg - ? 
                                       WHERE bean_id = ? AND store_id = ?");
            if (!$invStmt) throw new Exception("Prepare failed: " . $conn->error);
            $invStmt->bind_param('dii', $quantity, $bean_id, $store_id);
            if (!$invStmt->execute()) throw new Exception("Execute failed: " . $invStmt->error);
            $invStmt->close();

            // insert sale item
            $itemStmt = $conn->prepare("INSERT INTO sale_items 
                                        (sale_id, bean_id, quantity, unit_price, subtotal)
                                        VALUES (?, ?, ?, ?, ?)");
            if (!$itemStmt) throw new Exception("Prepare failed: " . $conn->error);
            $itemStmt->bind_param('iiddd', $sale_id, $bean_id, $quantity, $unit_price, $subtotal);
            if (!$itemStmt->execute()) throw new Exception("Execute failed: " . $itemStmt->error);
            $itemStmt->close();
        }

        // update sale total
        $saleStmt = $conn->prepare("UPDATE sale SET total_amount = ? WHERE sale_id = ?");
        if (!$saleStmt) throw new Exception("Prepare failed: " . $conn->error);
        $saleStmt->bind_param('di', $total, $sale_id);
        if (!$saleStmt->execute()) throw new Exception("Execute failed: " . $saleStmt->error);
        $saleStmt->close();

        // insert payment - status based on payment method
        $status = ($payment_method === 'CASH') ? 'PENDING' : 'PAID';

        $payStmt = $conn->prepare("INSERT INTO sale_payment 
                                   (sale_id, payment_date, amount_paid, currency_code, payment_method, payment_status)
                                   VALUES (?, ?, ?, ?, ?, ?)");
        if (!$payStmt) throw new Exception("Prepare failed: " . $conn->error);
        $payStmt->bind_param('isdsss', $sale_id, $sale_date, $total, $currency, $payment_method, $status);
        if (!$payStmt->execute()) throw new Exception("Execute failed: " . $payStmt->error);
        $payStmt->close();

        $conn->commit();

        // clear cart
        unset($_SESSION['cart']);

        echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Order failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()]);
    } finally {
        $conn->close();
    }
}
?>