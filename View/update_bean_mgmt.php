<?php
session_start();
include("../config/db-connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bean_id     = $_POST['bean_id'];
    $bean_name   = $_POST['bean_name'];
    $variety     = $_POST['variety'];
    $origin_province = $_POST['origin_province'];
    $roast_level = $_POST['roast_level'];
    $price_per_kg = $_POST['price_per_kg'];
    $supplier    = $_POST['supplier'];
    $description = $_POST['description'] ? $_POST['description'] : null;

    $query = "UPDATE coffee_bean 
              SET bean_name=?, variety=?, origin_province_id=?, roast_level=?, price_per_kg=?, supplier_id=?, description=?
              WHERE bean_id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssisdisi", $bean_name, $variety, $origin_province, $roast_level, $price_per_kg, $supplier, $description, $bean_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Coffee Bean Updated Successfully']);
    } else {
        echo json_encode(['error' => 'Failed to Update Coffee Bean']);
    }

    $stmt->close();
    $conn->close();
    exit;

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // fetch current bean data to prefill the form
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT cb.*, p.province_name FROM coffee_bean cb JOIN province p ON cb.origin_province_id = p.province_id WHERE bean_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bean = $result->fetch_assoc();
    $stmt->close();

    if (!$bean) {
        echo json_encode(['error' => 'Bean not found']);
        exit;
    }

    // fetch provinces and suppliers for dropdowns
    $provinces = mysqli_query($conn, "SELECT * FROM province ORDER BY province_name ASC");
    $suppliers = mysqli_query($conn, "SELECT * FROM supplier ORDER BY supplier_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Bean</title>
    <link rel="stylesheet" href="../../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; padding: 40px; color: #3b1f0e; }
        h2 { margin-bottom: 24px; }
        form { display: flex; flex-direction: column; gap: 14px; max-width: 500px; }
        label { font-weight: 600; font-size: 14px; }
        input, select, textarea {
            padding: 10px 12px;
            border: 1px solid #c8b8a8;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #3b1f0e;
        }
        .btn-row { display: flex; gap: 12px; margin-top: 8px; }
        button[type="submit"] {
            background-color: #3b1f0e;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        button[type="submit"]:hover { background-color: #5a3020; }
        a.cancel {
            padding: 10px 24px;
            border: 1px solid #3b1f0e;
            border-radius: 6px;
            text-decoration: none;
            color: #3b1f0e;
            font-weight: 600;
            font-size: 14px;
        }
        #msg { margin-top: 16px; font-weight: 600; }
    </style>
</head>
<body>
    <h2>Edit Bean</h2>
    <form id="update-form">
        <input type="hidden" name="bean_id" value="<?= $bean['bean_id'] ?>">

        <label>Bean Name</label>
        <input type="text" name="bean_name" value="<?= $bean['bean_name'] ?>" required>

        <label>Variety</label>
        <input type="text" name="variety" value="<?= $bean['variety'] ?>">

        <label>Origin Province</label>
        <select name="origin_province">
            <?php while ($p = mysqli_fetch_assoc($provinces)): ?>
                <option value="<?= $p['province_id'] ?>" <?= $p['province_id'] == $bean['origin_province_id'] ? 'selected' : '' ?>>
                    <?= $p['province_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Roast Level</label>
        <select name="roast_level">
            <?php foreach (['Light', 'Medium', 'Medium-Dark', 'Dark'] as $level): ?>
                <option value="<?= $level ?>" <?= $bean['roast_level'] == $level ? 'selected' : '' ?>><?= $level ?></option>
            <?php endforeach; ?>
        </select>

        <label>Price per kg (₱)</label>
        <input type="number" step="0.01" name="price_per_kg" value="<?= $bean['price_per_kg'] ?>" required>

        <label>Supplier</label>
        <select name="supplier">
            <?php while ($s = mysqli_fetch_assoc($suppliers)): ?>
                <option value="<?= $s['supplier_id'] ?>" <?= $s['supplier_id'] == $bean['supplier_id'] ? 'selected' : '' ?>>
                    <?= $s['supplier_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="3"><?= $bean['description'] ?></textarea>

        <div class="btn-row">
            <button type="submit">Save Changes</button>
            <a class="cancel" href="../../management.php">Cancel</a>
        </div>
    </form>

    <p id="msg"></p>

    <script>
        document.getElementById('update-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const response = await fetch('update_bean.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            const msg = document.getElementById('msg');
            if (data.success) {
                msg.style.color = 'green';
                msg.textContent = data.success;
                setTimeout(() => window.location.href = '../../management.php', 1000);
            } else {
                msg.style.color = 'red';
                msg.textContent = data.error;
            }
        });
    </script>
</body>
</html>
<?php
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
?>