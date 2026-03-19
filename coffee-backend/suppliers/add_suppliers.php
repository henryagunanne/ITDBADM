<?php
session_start();
include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_name  = $_POST['supplier_name'];
    $contact_number = $_POST['contact_number'];
    $email          = $_POST['email'];
    $address        = $_POST['address'];
    $city_id        = $_POST['city_id'];

    $stmt = $conn->prepare("INSERT INTO supplier (supplier_name, contact_number, email, address, city_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $supplier_name, $contact_number, $email, $address, $city_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=supplier-management');
        exit;
    } else {
        // $error = 'Failed to add supplier.';
        // $stmt->close();
        $error = 'Failed to add supplier: ' . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Supplier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Notable&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/common/style_mgmt.css">
</head>
<body>
    <header>
        <nav>
            <select>
                <option>Manila Branch</option>
                <option>Laguna Branch</option>
            </select>
            <img src="../../public/common/logo.png" class="logo" alt="Cool Beans Logo" />
            <a href=""><img src="../../public/common/user.png" class="icons" /></a>
        </nav>
    </header>

    <div class="breadcrumb">
        <p><a href="../management/management.php?tab=supplier-management">Management</a> &gt; Add Supplier</p>
    </div>

    <h2>Add New Supplier</h2>

    <div class="form-container">
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: 600;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="add_suppliers.php">

            <div class="form-group">
                <label>Supplier Name</label>
                <input type="text" name="supplier_name" placeholder="e.g. Benguet Farms Co." required>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" placeholder="e.g. 09171234567">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="e.g. supplier@email.com">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" placeholder="e.g. 123 Farm Road">
            </div>

            <div class="form-group">
                <label>City ID</label>
                <input type="number" name="city_id" placeholder="e.g. 1">
            </div>

            <div class="btn-row">
                <button type="submit">Add Supplier</button>
                <a class="cancel-btn" href="../management/management.php?tab=supplier-management">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>