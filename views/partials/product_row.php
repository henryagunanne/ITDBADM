<?php foreach ($beans as $row): ?>
<div class="product-row">
    <div class="product-items">
        <?php foreach ($row as $bean): ?>
            <div class="bean-item" onclick="window.location.href='/view/beans/<?= $bean['bean_id'] ?>'">
                <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
                <p>PHP <?= htmlspecialchars($bean['price_per_kg']) ?>/kg</p>
                <img src="<?= htmlspecialchars($bean['bean_image_path'] ?? '') ?>">
                
                <div class="controls">
                    <div class="qty-selector">
                        <button>-</button> <span>0</span> <button>+</button>
                    </div>
                    <button class="add-btn">Add to Cart</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>