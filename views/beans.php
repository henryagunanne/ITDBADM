<?php
session_start();
require_once '../config/db-connect.php';

// --- FETCH BEANS ---
$bean_query = "SELECT bean_id, bean_name, price_per_kg, bean_image_path FROM coffee_bean LIMIT 4";
$bean_result = mysqli_query($conn, $bean_query);
$beansRaw = [];
while ($row = mysqli_fetch_assoc($bean_result)) {
    $beansRaw[] = $row;
}
$beans = [array_values($beansRaw)];

// --- FETCH PROVINCES ---
$province_result = mysqli_query($conn, "SELECT province_id, province_name FROM province");
$provinces = [];
while ($row = mysqli_fetch_assoc($province_result)) {
    $provinces[] = $row;
}

// --- FETCH VARIETIES ---
$variety_result = mysqli_query($conn, "SELECT DISTINCT variety FROM coffee_bean");
$varieties = [];
while ($row = mysqli_fetch_assoc($variety_result)) {
    $varieties[] = $row['variety'];
}

// --- CART ---
$items = [];
$total_price = 0;

$location = 'Home > Beans';
$title = 'Coffee Beans';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>
<h2>Coffee Beans</h2>
<div class="main-container">
    <?php include __DIR__ . '/partials/sidenav.php'; ?>
    <?php include __DIR__ . '/partials/beans.php'; ?>
</div>
<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/../views/layouts/layout.php';
?>