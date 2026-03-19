<?php
session_start();
include("../config/db-connect.php");

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bean_name       = $_POST['bean_name'];
    $variety         = $_POST['variety'];
    $origin_province = $_POST['origin_province'];
    $roast_level     = $_POST['roast_level'];
    $price_per_kg    = $_POST['price_per_kg'];
    $supplier        = $_POST['supplier'];
    $description     = !empty($_POST['description']) ? $_POST['description'] : null;

    $stmt = $conn->prepare("INSERT INTO coffee_bean (bean_name, variety, origin_province_id, roast_level, price_per_kg, supplier_id, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisdis", $bean_name, $variety, $origin_province, $roast_level, $price_per_kg, $supplier, $description);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=product-management');
        exit;
    } else {
        $error = 'Failed to add bean. Please try again.';
        $stmt->close();
    }
}

// fetch provinces and suppliers for dropdowns
$provinces = mysqli_query($conn, "SELECT * FROM province ORDER BY province_name ASC");
$suppliers = mysqli_query($conn, "SELECT * FROM supplier ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Bean</title>
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
        <p><a href="../management/management.php?tab=product-management">Management</a> &gt; Add Bean</p>
    </div>

    <h2>Add New Bean</h2>

    <div class="form-container">
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: 600;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="add_bean.php">

            <div class="form-group">
                <label>Bean Name</label>
                <input type="text" name="bean_name" placeholder="e.g. Benguet Arabica" required>
            </div>

            <div class="form-group">
                <label>Variety</label>
                <input type="text" name="variety" placeholder="e.g. Arabica, Robusta">
            </div>

            <div class="form-group">
                <label>Origin Province</label>
                <select name="origin_province" required>
                    <option value="">-- Select Province --</option>
                    <?php while ($p = mysqli_fetch_assoc($provinces)): ?>
                        <option value="<?= $p['province_id'] ?>"><?= $p['province_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Roast Level</label>
                <select name="roast_level">
                    <option value="Light">Light</option>
                    <option value="Medium">Medium</option>
                    <option value="Medium-Dark">Medium-Dark</option>
                    <option value="Dark">Dark</option>
                </select>
            </div>

            <div class="form-group">
                <label>Price per kg (₱)</label>
                <input type="number" step="0.01" name="price_per_kg" placeholder="e.g. 350.00" required>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier" required>
                    <option value="">-- Select Supplier --</option>
                    <?php while ($s = mysqli_fetch_assoc($suppliers)): ?>
                        <option value="<?= $s['supplier_id'] ?>"><?= $s['supplier_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Description <span style="font-weight:400; font-size:12px;">(optional)</span></label>
                <textarea name="description" rows="3" placeholder="Short description of this bean..."></textarea>
            </div>

            <div class="btn-row">
                <button type="submit">Add Bean</button>
                <a class="cancel-btn" href="../management/management.php?tab=product-management">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>