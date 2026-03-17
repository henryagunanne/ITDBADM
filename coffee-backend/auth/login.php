<?php
    session_start();
    include "../config/db-connect.php";

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Secure hashed password check
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // check if user status is active
            if ($user['is_active'] !== 1) {
                echo json_encode(['error' => "Account has been deleted. Please contact support."]);
                $stmt->close();
                $conn->close();
                exit;
            }
        
            if (password_verify($password, $user['password'])) {
        
                // store session data
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['linked_customer'] = $user['linked_customer_id'];
                $_SESSION['linked_store'] = $user['linked_store_id'];

            
                // redirect based on role
                $role = 'CUSTOMER';
                if ($user['user_type'] === 'ADMIN') {
                    $_SESSION['is_admin'] = true;
                    $role = 'ADMIN';
                } elseif ($user['user_type'] === 'STAFF') {
                    $_SESSION['is_staff'] = true;
                    $role = 'STAFF';
                }



                echo json_encode([
                    'success' => "Login successful!",
                    'role' => $role,
                ]);

                $stmt->close();
                $conn->close();
                exit;
        
            } else {
                echo json_encode(['error' => "Incorrect password!"]);
                //header("Location: login.php");
                $stmt->close();
                $conn->close();
                exit;
            }
        } else {
            echo json_encode(['error' => "Username not found!"]);
            $stmt->close();
            $conn->close();
            exit;
        }
    }


?>