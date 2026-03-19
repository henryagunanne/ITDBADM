<?php
session_start();
require_once '../coffee-backend/config/db-connect.php';

// --- CART ITEMS (from session) ---
$items = $_SESSION['cart'] ?? [];
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

// --- FETCH CITIES ---
$city_result = mysqli_query($conn, "SELECT city_id, city_name FROM city");
$cities = [];
while ($row = mysqli_fetch_assoc($city_result)) {
    $cities[] = $row;
}

// --- CART ---
$location = 'Home > Checkout';
$title = 'Checkout';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
include __DIR__ . '/partials/checkout/checkout_container.php';
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/../views/layouts/layout.php';
?>