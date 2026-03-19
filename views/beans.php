<?php
session_start();
require_once '../coffee-backend/config/db-connect.php';

// --- FETCH BEANS ---
$bean_query = "SELECT bean_id, bean_name, price_per_kg FROM coffee_bean";
$bean_result = mysqli_query($conn, $bean_query);
$beans = [];
while ($row = mysqli_fetch_assoc($bean_result)) {
    $beans[] = $row;
}

// --- FETCH PROVINCES ---
$province_result = mysqli_query($conn, "SELECT province_id, province_name FROM province");
$provinces = [];
while ($row = mysqli_fetch_assoc($province_result)) {
    $provinces[] = $row;
}

// --- CART ---
$items = $_SESSION['cart'] ?? [];
$total_price = 0;

$location = 'Home > Beans';
$title = 'Coffee Beans';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>
<h2>Coffee Beans</h2>
<div class="main-container">
    <?php include __DIR__ . '/partials/view/beans/sidenav.php'; ?>
    <?php include __DIR__ . '/partials/view/beans/beans.php'; ?>
</div>
<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/layouts/layout.php';
?>