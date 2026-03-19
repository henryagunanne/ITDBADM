<?php
session_start();
require_once 'config/db-connect.php';

// --- FETCH BEANS ---
$bean_query = "SELECT bean_id, bean_name, price_per_kg FROM coffee_bean LIMIT 4";
$bean_result = mysqli_query($conn, $bean_query);
$beans = [];
while ($row = mysqli_fetch_assoc($bean_result)) {
    $beans[] = $row;
}

// --- FETCH FARMER FACT ---
$supplier_query = "SELECT supplier_name, address FROM supplier LIMIT 1";
$supplier_result = mysqli_query($conn, $supplier_query);
$supplier = mysqli_fetch_assoc($supplier_result);
$ffact = [
    'title'       => 'Meet Our Farmer: ' . $supplier['supplier_name'],
    'description' => 'Based in ' . $supplier['address'] . ', our suppliers grow only the finest beans.'
];

// --- TESTIMONIALS ---
$testimonials = [
    ['title' => 'Amazing Coffee!',  'description' => 'Best beans I have ever tried.',  'author' => 'Juan D.'],
    ['title' => 'Great Quality',    'description' => 'Smooth and rich flavor.',         'author' => 'Maria S.'],
    ['title' => 'Highly Recommend', 'description' => 'Will definitely order again.',    'author' => 'Carlo R.'],
];

$title = 'Cool Beans';

// --- CART ---
$items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($items as $item) {
    $total_price += $item['bean']['price_per_kg'] * $item['quantity'];
}

// --- RENDER ---
ob_start();
include '../views/partials/header.php';
include '../views/partials/home/hero.php';
include '../views/partials/home/farmer_fact.php';
include '../views/partials/home/home_testimonials.php';
include '../views/partials/footer.php';
$body = ob_get_clean();

// pass body content to layout
include '../views/layouts/layout.php';
?>