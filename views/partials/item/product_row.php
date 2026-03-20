<?php if (empty($beans)): ?>
    <p>No beans found.</p>
<?php endif; ?>
<div class="product-row">
    <div class="product-items">
        <?php foreach ($beans as $bean): ?>
            <div class="bean-item" data-id="<?= $bean['bean_id'] ?>">
                <div class="bean-link" onclick="window.location.href='/itdbadm-mp/views/item.php?id=<?= $bean['bean_id'] ?>'">
                    <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
                    <p>PHP <?= number_format($bean['price_per_kg'], 2) ?>/kg</p>
                    <img src="/itdbadm-mp/public/common/coffee-bag.png">
                </div>
                <div class="controls">
                    <div class="qty-selector">
                        <button class="qty-btn minus" data-id="<?= $bean['bean_id'] ?>">-</button>
                        <span class="qty-display" id="qty-<?= $bean['bean_id'] ?>">1</span>
                        <button class="qty-btn plus" data-id="<?= $bean['bean_id'] ?>">+</button>
                    </div>
                    <button class="add-btn" data-id="<?= $bean['bean_id'] ?>" onclick="addToCart(<?= $bean['bean_id'] ?>)">Add to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>