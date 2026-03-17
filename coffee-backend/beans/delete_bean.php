<?php
    session_start(); 
    include("../config/db-connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bean_id = $_POST['bean_id'];

        $query = "DELETE FROM coffee_bean WHERE bean_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $bean_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => `Coffee Bean Deleted Successfully`]);
            $stmt->close();
            $conn->close();
            exit;
        } else {
            echo json_encode(['error' => 'Failed to Delete Coffee Bean']);
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