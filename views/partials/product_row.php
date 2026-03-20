<?php foreach ($beans as $row): ?>
<div class="product-row">
    <div class="product-items">
        <?php foreach ($row as $bean): ?>
            <div class="bean-item" onclick="window.location.href='/itdbadm-mp/views/item.php?id=<?= $bean['bean_id'] ?>'">
                <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
                <p>PHP <?= htmlspecialchars($bean['price_per_kg']) ?>/kg</p>
                <img src="/itdbadm-mp/public/common/coffee-bag.png">
                
                <div class="controls" onclick="event.stopPropagation()">
                    <div class="qty-selector">
                        <button onclick="changeQty(this, -1)">-</button>
                        <span>1</span>
                        <button onclick="changeQty(this, 1)">+</button>
                    </div>
                    <button class="add-btn" onclick="addToCart(<?= $bean['bean_id'] ?>, getQty(this))">Add to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>