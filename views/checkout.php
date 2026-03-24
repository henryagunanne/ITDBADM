<?php
session_start();
require_once '../coffee-backend/config/db-connect.php';

// // redirect if not logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: /itdbadm-mp/coffee-backend/index.php');
//     exit;
// }

// --- CART ITEMS ---
$items = $_SESSION['cart'] ?? [];

// redirect if cart is empty
if (empty($items)) {
    header('Location: /itdbadm-mp/views/beans.php');
    exit;
}

$total_price = 0;
foreach ($items as $item) {
    $total_price += $item['bean']['price_per_kg'] * $item['quantity'];
}

// --- FETCH STORES ---
$store_result = mysqli_query($conn, "SELECT store_id, store_name FROM store");
$stores = [];
while ($row = mysqli_fetch_assoc($store_result)) {
    $stores[] = $row;
}

// --- FETCH PROVINCES ---
$prov_result = mysqli_query($conn, "SELECT province_id, province_name FROM province");
$provinces = [];
while ($row = mysqli_fetch_assoc($prov_result)) {
    $provinces[] = $row;
}

$location = 'Home > Checkout';
$title    = 'Checkout';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
include __DIR__ . '/partials/checkout/checkout_container.php';
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/layouts/layout.php';
?>