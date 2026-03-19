<?php
session_start();
include("../config/db-connect.php");

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id   = $_POST['user_id'];
    $username  = $_POST['username'];
    $role      = $_POST['role'];
    $is_active = $_POST['is_active'];

    $stmt = $conn->prepare("UPDATE users SET username=?, role=?, is_active=? WHERE user_id=?");
    $stmt->bind_param("ssii", $username, $role, $is_active, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: ../management/management.php?tab=user-management');
        exit;
    } else {
        $error = 'Failed to update user.';
        $stmt->close();
    }
}

// fetch user data to prefill form
if (!isset($_GET['id'])) {
    header('Location: ../management/management.php?tab=user-management');
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
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
        <p><a href="../management/management.php">Management</a> &gt; Edit User</p>
    </div>

    <h2>Edit User</h2>

    <div class="form-container">
        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: 600;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_user.php?id=<?= $user['user_id'] ?>">
            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= $user['username'] ?>" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="admin"    <?= $user['role'] == 'admin'    ? 'selected' : '' ?>>Admin</option>
                    <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                </select>
            </div>

            <div class="form-group">
                <label>Is Active</label>
                <select name="is_active">
                    <option value="1" <?= $user['is_active'] == 1 ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= $user['is_active'] == 0 ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="btn-row">
                <button type="submit">Save Changes</button>
                <a class="cancel-btn" href="../management/management.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>