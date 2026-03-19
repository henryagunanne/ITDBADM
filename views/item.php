<?php
session_start();
require_once '../config/db-connect.php';

// --- FETCH SELECTED BEAN ---
$bean_id = $_GET['id'] ?? null;
if (!$bean_id) {
    header('Location: /itdbadm-mp/views/beans.php');
    exit;
}

$query = "SELECT cb.*, p.province_name 
          FROM coffee_bean cb
          JOIN province p ON cb.origin_province_id = p.province_id
          WHERE cb.bean_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $bean_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$selected = mysqli_fetch_assoc($result);

if (!$selected) {
    header('Location: /itdbadm-mp/views/beans.php');
    exit;
}

// --- FETCH RELATED BEANS ---
$bean_result = mysqli_query($conn, "SELECT bean_id, bean_name, price_per_kg FROM coffee_bean LIMIT 4");
$beans = [];
while ($row = mysqli_fetch_assoc($bean_result)) {
    $beans[] = $row;
}

// --- CART ---
$items = $_SESSION['cart'] ?? [];
$total_price = 0;

$location = 'Home > Beans > ' . $selected['bean_name'];
$title = $selected['bean_name'];

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
include __DIR__ . '/partials/item/product.php';
include __DIR__ . '/partials/item/product_row.php';
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/../views/layouts/layout.php';
?>