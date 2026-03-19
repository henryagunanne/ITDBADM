<?php
session_start();
require_once '../coffee-backend/config/db-connect.php';

// --- SEARCH ---
$search = $_GET['search'] ?? '';
$search_clause = '';
$search_params = [];

if (!empty($search)) {
    $words = explode(' ', trim($search));
    $conditions = [];
    foreach ($words as $word) {
        $word = mysqli_real_escape_string($conn, $word);
        $conditions[] = "(cb.bean_name LIKE '%$word%' OR cb.variety LIKE '%$word%' OR p.province_name LIKE '%$word%' OR cb.roast_level LIKE '%$word%')";
    }
    $search_clause = 'AND ' . implode(' AND ', $conditions);
}

// --- FETCH BEANS ---
$bean_query = "SELECT bean_id, bean_name, price_per_kg FROM coffee_bean cb
               JOIN province p ON cb.origin_province_id = p.province_id
               WHERE 1=1 $search_clause
               ORDER BY cb.bean_name ASC";
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

$location = 'Home > Beans' . (!empty($search) ? ' > Search: ' . htmlspecialchars($search) : '');
$title = 'Coffee Beans';

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