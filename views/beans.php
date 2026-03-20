<?php
session_start();
require_once '../coffee-backend/config/db-connect.php';

// --- SEARCH ---
$search     = $_GET['search']      ?? '';
$sort       = $_GET['sort']        ?? 'A-Z';
$origin     = $_GET['origin']      ?? '';
$variety    = $_GET['variety']     ?? '';
$roast      = $_GET['roast_level'] ?? '';
$min_price  = $_GET['min_price']   ?? '';
$max_price  = $_GET['max_price']   ?? '';

$where = "WHERE 1=1";

// search
if (!empty($search)) {
    $words = explode(' ', trim($search));
    foreach ($words as $word) {
        $word   = mysqli_real_escape_string($conn, $word);
        $where .= " AND (cb.bean_name LIKE '%$word%' OR cb.variety LIKE '%$word%' OR p.province_name LIKE '%$word%' OR cb.roast_level LIKE '%$word%')";
    }
}

// filters
if (!empty($origin))    $where .= " AND cb.origin_province_id = " . (int)$origin;
if (!empty($variety))   $where .= " AND cb.variety = '" . mysqli_real_escape_string($conn, $variety) . "'";
if (!empty($roast))     $where .= " AND cb.roast_level = '" . mysqli_real_escape_string($conn, $roast) . "'";
if (!empty($min_price)) $where .= " AND cb.price_per_kg >= " . (float)$min_price;
if (!empty($max_price)) $where .= " AND cb.price_per_kg <= " . (float)$max_price;

// sort
switch ($sort) {
    case 'Z-A':       $order = "cb.bean_name DESC"; break;
    case 'low-high':  $order = "cb.price_per_kg ASC"; break;
    case 'high-low':  $order = "cb.price_per_kg DESC"; break;
    default:          $order = "cb.bean_name ASC"; break;
}

// --- FETCH BEANS ---
$bean_query  = "SELECT cb.bean_id, cb.bean_name, cb.price_per_kg
                FROM coffee_bean cb
                JOIN province p ON cb.origin_province_id = p.province_id
                $where
                ORDER BY $order";
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

// --- FETCH VARIETIES ---
$variety_result = mysqli_query($conn, "SELECT DISTINCT variety FROM coffee_bean WHERE variety IS NOT NULL");
$varieties = [];
while ($row = mysqli_fetch_assoc($variety_result)) {
    $varieties[] = $row['variety'];
}

// --- CART ---
$items       = $_SESSION['cart'] ?? [];
$total_price = 0;

$location = 'Home > Beans' . (!empty($search) ? ' > Search: ' . htmlspecialchars($search) : '');
$title    = 'Coffee Beans';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>
<h2>Coffee Beans</h2>
<?php if (!empty($search)): ?>
    <p style="text-align:center; color:#5D372A;">
        Showing results for: <strong><?= htmlspecialchars($search) ?></strong>
        <a href="/itdbadm-mp/views/beans.php" style="color:#EA672D; margin-left:10px;">Clear</a>
    </p>
<?php endif; ?>
<div class="main-container">
    <?php include __DIR__ . '/partials/view/beans/sidenav.php'; ?>
    <?php include __DIR__ . '/partials/view/beans/beans.php'; ?>
</div>
<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();

include __DIR__ . '/layouts/layout.php';
?>