<?php if (empty($beans)): ?>
    <p>No beans found.</p>
<?php endif; ?>
<div class="product-row">
    <div class="product-items">
        <?php foreach ($beans as $bean): ?>
            <div class="bean-item">
                <a href="/itdbadm-mp/views/view/beans/item.php?id=<?= $bean['bean_id'] ?>">
                    <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
                    <p>PHP <?= number_format($bean['price_per_kg'], 2) ?>/kg</p>
                    <img src="/itdbadm-mp/public/common/coffee-bag.png">
                </a>
                <div class="controls">
                    <div class="qty-selector">
                        <button>-</button> <span>1</span> <button>+</button>
                    </div>
                    <button class="add-btn">Add to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>