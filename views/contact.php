<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$is_admin     = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$items        = $_SESSION['cart'] ?? [];
$total_price  = 0;

$location = 'Home > Contact Us';
$title    = 'Contact Us';

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>

<style>
    .contact-page {
        width: 90%;
        max-width: 900px;
        margin: 40px auto 60px auto;
    }

    .contact-page h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .contact-card {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 40px;
        border: 2px solid #3A5635;
        border-radius: 24px;
        padding: 50px 40px;
    }

    .contact-card img {
        width: 200px;
        flex-shrink: 0;
        object-fit: contain;
    }

    .contact-info-block {
        display: flex;
        flex-direction: column;
        gap: 24px;
        color: #5D372A;
        font-size: 14px;
        line-height: 1.7;
    }

    .contact-info-block h4 {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #3A5635;
    }

    .contact-info-block p {
        margin: 0;
    }

    .contact-info-block a {
        color: #EA672D;
        text-decoration: none;
        font-weight: 600;
    }

    .contact-info-block a:hover {
        text-decoration: underline;
    }
</style>

<div class="contact-page">
    <h2>Contact Us</h2>
    <div class="contact-card">
        <img src="/itdbadm-mp/public/common/logo.png" alt="Cool Beans Logo" />
        <div class="contact-info-block">
            <div>
                <h4>Contact Our Customer Support:</h4>
                <p>
                    If you have questions about your order or want to learn more about Cool Beans,
                    feel free to email us at <a href="mailto:support@coolbeans.ph">support@coolbeans.ph</a>.<br>
                    Our customer service team is available Monday–Friday, 9:00 AM to 6:00 PM (PHT).
                </p>
            </div>
            <div>
                <h4>Press & Collaborations:</h4>
                <p>
                    Interested in featuring Cool Beans or working with us? Reach out
                    at <a href="mailto:partnerships@coolbeans.ph">partnerships@coolbeans.ph</a>
                </p>
                <p>
                    Or send us a letter at:<br>
                    Cool Beans Coffee Co.<br>
                    Unit 305, Brew Hub Building<br>
                    Makati City, Metro Manila, Philippines 1227
                </p>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();
include __DIR__ . '/layouts/layout.php';
?>