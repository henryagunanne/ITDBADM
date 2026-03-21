<?php
session_start();
include __DIR__ . '/../config/db-connect.php';

// Redirect to home if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /itdbadm-mp/coffee-backend/index.php');
    exit;
}

$is_logged_in = true;
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Fetch customer profile linked to the logged-in user
$user_id = $_SESSION['user_id'];
$customer = null;
$orders = [];

$stmt = $conn->prepare("
    SELECT c.first_name, c.last_name, c.email, c.contact_number, c.address
    FROM users u
    JOIN customer c ON u.linked_customer_id = c.customer_id
    WHERE u.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

// Fetch order history
if ($customer) {
    $stmt = $conn->prepare("
        SELECT log_id, store_name, sale_date, total_amount, currency_code, payment_method
        FROM order_history_log
        WHERE customer_id = (
            SELECT linked_customer_id FROM users WHERE user_id = ?
        )
        ORDER BY logged_at DESC
    ");
    // not using trigger:
    // $stmt = $conn->prepare("
    //     SELECT s.sale_id, st.store_name, s.sale_date, s.total_amount,
    //            sp.currency_code, sp.payment_method
    //     FROM sale s
    //     JOIN store st ON s.store_id = st.store_id
    //     JOIN customer c ON s.customer_id = c.customer_id
    //     JOIN users u ON u.linked_customer_id = c.customer_id
    //     LEFT JOIN sale_payment sp ON sp.sale_id = s.sale_id
    //     WHERE u.user_id = ?
    //     ORDER BY s.sale_date DESC
    // ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>User Profile | Cool Beans</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Notable&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/itdbadm-mp/public/common/style.css">
    <style>
        .profile-page {
            width: 90%;
            max-width: 700px;
            margin: 120px auto 60px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .profile-page h2 {
            margin: 0;
        }

        .profile-card {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 40px;
            padding: 10px 0 30px 0;
            border-bottom: 2px solid #5D372A;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 0.6;
        }

        .profile-details {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 24px;
            align-items: center;
        }

        .profile-details .label {
            font-weight: 700;
            font-size: 14px;
            color: #5D372A;
        }

        .profile-details .value {
            font-size: 14px;
            color: #5D372A;
        }

        .order-history {
            width: 100%;
        }

        .order-history h3 {
            font-size: 22px;
            font-weight: 800;
            color: #5D372A;
            margin: 0 0 16px 0;
        }

        .order-table-wrapper {
            width: 100%;
            background-color: #ecddd1;
            border-radius: 16px;
            overflow: hidden;
            min-height: 160px;
        }

        table.order-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-size: 13px;
        }

        table.order-table th {
            background-color: transparent;
            color: #5D372A;
            font-weight: 700;
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(93,55,42,0.2);
        }

        table.order-table td {
            padding: 12px 16px;
            color: #5D372A;
            border-bottom: 1px solid rgba(93,55,42,0.1);
        }

        table.order-table tr:last-child td {
            border-bottom: none;
        }

        .empty-orders {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 120px;
            color: #9e7060;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<?php
$items = [];
$total_price = 0;
include __DIR__ . '/../../views/partials/header.php';
?>

<div class="profile-page">
    <h2>User Profile</h2>

    <?php if ($customer): ?>
    <div class="profile-card">
        <div class="profile-avatar">
            <img src="/itdbadm-mp/public/common/profile-pic.png" alt="User Avatar" />
        </div>
        <div class="profile-details">
            <span class="label">Name:</span>
            <span class="value"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></span>

            <span class="label">Email:</span>
            <span class="value"><?= htmlspecialchars($customer['email']) ?></span>

            <span class="label">Contact Number:</span>
            <span class="value"><?= htmlspecialchars($customer['contact_number'] ?? '—') ?></span>

            <span class="label">Address:</span>
            <span class="value"><?= htmlspecialchars($customer['address'] ?? '—') ?></span>
        </div>
    </div>

    <div class="order-history">
        <h3>Order History</h3>
        <div class="order-table-wrapper">
            <?php if (!empty($orders)): ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Store</th>
                        <th>Sale Date</th>
                        <th>Total Amount</th>
                        <th>Currency</th>
                        <th>Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['log_id']) ?></td>
                        <td><?= htmlspecialchars($order['store_name']) ?></td>
                        <td><?= htmlspecialchars($order['sale_date']) ?></td>
                        <td>PHP <?= number_format($order['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($order['currency_code'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($order['payment_method'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="empty-orders">No orders yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php else: ?>
        <p style="color:#9e7060; text-align:center;">No profile found for this account.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../views/partials/footer.php'; ?>

</body>
</html>
<?php $conn->close(); ?>




<?php
session_start();
include __DIR__ . '/../config/db-connect.php';

// Redirect to home if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /itdbadm-mp/coffee-backend/index.php');
    exit;
}

$is_logged_in = true;
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Fetch customer profile linked to the logged-in user
$user_id = $_SESSION['user_id'];
$customer = null;
$orders = [];

$stmt = $conn->prepare("
    SELECT c.first_name, c.last_name, c.email, c.contact_number, c.address
    FROM users u
    JOIN customer c ON u.linked_customer_id = c.customer_id
    WHERE u.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();