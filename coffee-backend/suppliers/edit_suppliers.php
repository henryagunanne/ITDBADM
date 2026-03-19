<?php
session_start();
include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id    = $_POST['supplier_id'];
    $supplier_name  = $_POST['supplier_name'];
    $contact_number = $_POST['contact_number'];
    $email          = $_POST['email'];
    $address        = $_POST['address'];
    $city_id        = $_POST['city_id'];

    $stmt = $conn->prepare("UPDATE supplier SET supplier_name=?, contact_number=?, email=?, address=?, city_id=? WHERE supplier_id=?");
    $stmt->bind_param("ssssii", $supplier_name, $contact_number, $email, $address, $city_id, $supplier_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=supplier-management');
        exit;
    } else {
        $error = 'Failed to update supplier.';
        $stmt->close();
    }
}

if (!isset($_GET['id'])) {
    header('Location: ../management/management.php');
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM supplier WHERE supplier_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$supplier = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$supplier) {
    echo "Supplier not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Supplier</title>
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
        <p><a href="../management/management.php">Management</a> &gt; Edit Supplier</p>
    </div>

    <h2>Edit Supplier</h2>

    <div class="form-container">
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: 600;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_supplier.php?id=<?= $supplier['supplier_id'] ?>">
            <input type="hidden" name="supplier_id" value="<?= $supplier['supplier_id'] ?>">

            <div class="form-group">
                <label>Supplier Name</label>
                <input type="text" name="supplier_name" value="<?= $supplier['supplier_name'] ?>" required>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="<?= $supplier['contact_number'] ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= $supplier['email'] ?>">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" value="<?= $supplier['address'] ?>">
            </div>

            <div class="form-group">
                <label>City ID</label>
                <input type="number" name="city_id" value="<?= $supplier['city_id'] ?>">
            </div>

            <div class="btn-row">
                <button type="submit">Save Changes</button>
                <a class="cancel-btn" href="../management/management.php?tab=supplier-management">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>