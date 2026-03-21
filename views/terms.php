<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$is_admin     = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$items        = $_SESSION['cart'] ?? [];
$total_price  = 0;

$location = 'Home > Terms & Conditions';
$title    = 'Terms & Conditions';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>

<style>
    .terms-page {
        width: 90%;
        max-width: 800px;
        margin: 40px auto 60px auto;
        color: #5D372A;
        font-size: 14px;
        line-height: 1.8;
    }

    .terms-page h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .terms-page h4 {
        font-size: 14px;
        font-weight: 700;
        margin: 24px 0 4px 0;
    }

    .terms-page p {
        margin: 0 0 8px 0;
    }
</style>

<div class="terms-page">
    <h2>Terms &amp; Conditions</h2>

    <h4>Terms and Conditions</h4>
    <p>Welcome to Cool Beans. By accessing and using our website, you agree to comply with the following terms and conditions. Please read them carefully.</p>

    <h4>1. General Use</h4>
    <p>By using this website, you confirm that you are at least 18 years old or accessing the site under the supervision of a parent or guardian. You agree not to use our products or services for any unlawful or unauthorized purpose.</p>

    <h4>2. Products and Pricing</h4>
    <p>All products listed are subject to availability. We reserve the right to modify prices, descriptions, and availability at any time without prior notice. While we strive for accuracy, we do not guarantee that all product details are error-free.</p>

    <h4>3. Orders and Payments</h4>
    <p>Once an order is placed, you will receive a confirmation email. Cool Beans reserves the right to cancel or refuse any order due to suspected fraud, incorrect pricing, or stock issues. Payments must be completed through our approved payment methods.</p>

    <h4>4. Shipping and Delivery</h4>
    <p>Delivery times may vary depending on your location. While we aim to deliver orders promptly, Cool Beans is not responsible for delays caused by courier services or unforeseen circumstances.</p>

    <h4>5. Returns and Refunds</h4>
    <p>Customers may request returns or refunds within [insert number] days of receiving the product, provided items are unused and in original packaging. Certain products may not be eligible for return due to hygiene or quality reasons.</p>

    <h4>6. Intellectual Property</h4>
    <p>All content on this website, including logos, text, images, and design, is the property of Cool Beans and may not be copied, reproduced, or used without permission.</p>

    <h4>7. Limitation of Liability</h4>
    <p>Cool Beans shall not be held liable for any indirect, incidental, or consequential damages arising from the use of our products or website.</p>

    <h4>8. Privacy</h4>
    <p>We are committed to protecting your personal information. Any data collected will be handled in accordance with our Privacy Policy.</p>

    <h4>9. Changes to Terms</h4>
    <p>Cool Beans reserves the right to update or modify these terms at any time. Continued use of the website constitutes acceptance of any changes.</p>

    <h4>10. Contact Us</h4>
    <p>For any questions regarding these Terms and Conditions, please contact us at: <a href="mailto:support@coolbeans.ph" style="color:#EA672D; font-weight:600;">support@coolbeans.ph</a></p>
</div>

<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();
include __DIR__ . '/layouts/layout.php';
?>