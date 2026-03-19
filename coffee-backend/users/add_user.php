<?php
session_start();
include("../config/db-connect.php");

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = $_POST['username'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role      = $_POST['role'];
    $is_active = $_POST['is_active'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, is_active) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $password, $role, $is_active);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=user-management');
        exit;
    } else {
        $error = 'Failed to add user.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
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
        <p><a href="../management/management.php">Management</a> &gt; Add User</p>
    </div>

    <h2>Add New User</h2>

    <div class="form-container">
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: 600;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="add_user.php">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="e.g. juan_dela_cruz" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Is Active</label>
                <select name="is_active">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="btn-row">
                <button type="submit">Add User</button>
                <a class="cancel-btn" href="../management/management.php?tab=user-management">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>