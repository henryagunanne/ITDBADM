<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$is_admin     = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$items        = $_SESSION['cart'] ?? [];
$total_price  = 0;

$location = 'Home > How to Order';
$title    = 'How to Order';

$steps = [
    ['title' => 'Browse Our Products',  'desc' => 'Explore our selection of locally sourced coffee beans and choose your preferred variety.'],
    ['title' => 'Add to Cart',          'desc' => 'Click "Add to Cart" on the items you want to purchase.'],
    ['title' => 'Review Your Order',    'desc' => 'Go to your cart and check your selected items, quantities, and total amount.'],
    ['title' => 'Proceed to Checkout',  'desc' => 'Enter your shipping details and select your preferred payment method.'],
    ['title' => 'Confirm Your Order',   'desc' => 'Review all details, then click "Place Order" to complete your purchase.'],
    ['title' => 'Order Confirmation',   'desc' => 'You will receive a confirmation email with your order details.'],
    ['title' => 'Delivery',             'desc' => 'Sit back and relax while we prepare and deliver your coffee straight to your doorstep.'],
];

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>

<style>
    .how-to-page {
        width: 90%;
        max-width: 700px;
        margin: 40px auto 60px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .how-to-page h2 {
        margin: 0 0 10px 0;
    }

    .step-card {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 24px;
        background-color: #D2E8FF;
        border-radius: 16px;
        padding: 24px 30px;
        box-sizing: border-box;
    }

    .step-number {
        font-size: 52px;
        font-weight: 800;
        color: #5D372A;
        min-width: 50px;
        line-height: 1;
    }

    .step-text h4 {
        font-size: 15px;
        font-weight: 700;
        color: #5D372A;
        margin: 0 0 4px 0;
    }

    .step-text p {
        font-size: 13px;
        color: #5D372A;
        margin: 0;
        line-height: 1.6;
    }
</style>

<div class="how-to-page">
    <h2>How to Order</h2>
    <?php foreach ($steps as $i => $step): ?>
    <div class="step-card">
        <div class="step-number"><?= $i + 1 ?></div>
        <div class="step-text">
            <h4><?= htmlspecialchars($step['title']) ?></h4>
            <p><?= htmlspecialchars($step['desc']) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();
include __DIR__ . '/layouts/layout.php';
?>