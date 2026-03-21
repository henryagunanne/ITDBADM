<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$is_admin     = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
$items        = $_SESSION['cart'] ?? [];
$total_price  = 0;

$location = 'Home > About Us';
$title    = 'About Us';

$farmers = [
    [
        'name'        => 'Nathaniel Isidro',
        'description' => 'Nathaniel Isidro is a young, passionate farmer experimenting with modern techniques while preserving local traditions, producing aromatic coffee with a unique, vibrant profile.',
        'image'       => '/itdbadm-mp/public/common/coffee-bean-2.png'
    ],
    [
        'name'        => 'South Farms',
        'description' => 'South Farms is a small-scale farm that grows coffee in the cool mountain slopes, carefully handpicking each bean to ensure rich, bold flavors with every cup.',
        'image'       => '/itdbadm-mp/public/common/coffee-bean-2.png'
    ],
    [
        'name'        => 'Leon Arabejo',
        'description' => 'Leon tends his coffee plants at dawn, nurturing them with traditional methods that give his beans a smooth, slightly sweet taste loved by his community.',
        'image'       => '/itdbadm-mp/public/common/coffee-bean-2.png'
    ],
];

ob_start();
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/breadcrumb.php';
?>

<style>
    .about-page {
        width: 90%;
        max-width: 900px;
        margin: 40px auto 60px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 60px;
    }

    /* HERO SECTION */
    .about-hero {
        text-align: center;
    }

    .about-hero h1 {
        font-family: 'Notable', sans-serif;
        font-size: 48px;
        color: #5D372A;
        margin: 0 0 30px 0;
    }

    .about-mission {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 40px;
        background-color: #fff8f3;
        border: 1px solid #e0cfc4;
        border-radius: 24px;
        padding: 40px;
        text-align: left;
    }

    .about-mission p {
        font-size: 15px;
        line-height: 1.8;
        color: #5D372A;
        flex: 1;
        margin: 0;
        text-align: center;
    }

    .about-mission img {
        width: 180px;
        object-fit: contain;
        flex-shrink: 0;
        opacity: 0.85;
    }

    /* FARMERS SECTION */
    .about-farmers {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
    }

    .about-farmers h2 {
        font-size: 28px;
        font-weight: 800;
        color: #5D372A;
        margin: 0;
    }

    .farmer-cards {
        display: flex;
        flex-direction: row;
        gap: 24px;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
    }

    .farmer-card {
        flex: 1;
        min-width: 220px;
        max-width: 280px;
        background-color: #fff8f3;
        border: 1px solid #e0cfc4;
        border-radius: 20px;
        padding: 30px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        text-align: center;
    }

    .farmer-card img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        opacity: 0.75;
    }

    .farmer-card h4 {
        font-size: 16px;
        font-weight: 800;
        color: #5D372A;
        margin: 0;
    }

    .farmer-card p {
        font-size: 13px;
        color: #7a4f3a;
        line-height: 1.6;
        margin: 0;
    }
</style>

<div class="about-page">

    <div class="about-hero">
        <h1>Coffee with a Cause</h1>
        <div class="about-mission">
            <p>
                Cool Beans supports local coffee farmers by sourcing and promoting Philippine-grown
                beans through a centralized online platform. By increasing their visibility and
                connecting them directly to consumers and businesses, the company helps farmers
                expand their market reach, boost demand for locally produced coffee, and encourage
                sustainable, community-based agriculture.
            </p>
            <img src="/itdbadm-mp/public/common/coffee-farmer.jpeg" alt="Coffee Farmer" style="border-radius:12px;" />
        </div>
    </div>

    <div class="about-farmers">
        <h2>Local Partner Farmers</h2>
        <div class="farmer-cards">
            <?php foreach ($farmers as $farmer): ?>
            <div class="farmer-card">
                <img src="<?= $farmer['image'] ?>" alt="<?= htmlspecialchars($farmer['name']) ?>" />
                <h4><?= htmlspecialchars($farmer['name']) ?></h4>
                <p><?= htmlspecialchars($farmer['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<?php
include __DIR__ . '/partials/footer.php';
$body = ob_get_clean();
include __DIR__ . '/layouts/layout.php';
?>