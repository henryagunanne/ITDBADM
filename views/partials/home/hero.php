<section class="top-container">
    <div class="top">
        <img src="../public/common/coffee.png" />
        <h1 style="font-family: Notable;">Cool Ka<br>Muna!</h1>
    </div>
    <p>Coffee with a cause.</p>
</section>

<section class="selection">
    <h2>Coffee Beans</h2>
    <?php foreach ($beans as $bean): ?>
        <div class="bean-item" onclick="window.location.href='/view/beans/<?= $bean['bean_id'] ?>'">
            <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
            <p>PHP <?= htmlspecialchars($bean['price_per_kg']) ?>/kg</p>
        </div>
    <?php endforeach; ?>
    <a href="/view/beans" style="color: #EA672D">See More</a>
</section>