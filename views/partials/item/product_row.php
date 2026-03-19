<?php foreach ($beans as $bean): ?>
    <div class="bean-item" onclick="window.location.href='/view/beans/<?= $bean['bean_id'] ?>'">
        <h4><?= htmlspecialchars($bean['bean_name']) ?></h4>
        <p>PHP <?= htmlspecialchars($bean['price_per_kg']) ?>/kg</p>
        <img src="/itdbadm-mp/public/common/coffee-bag.png">
        <div class="controls">
            <div class="qty-selector">
                <button>-</button> <span>0</span> <button>+</button>
            </div>
            <button class="add-btn">Add to Cart</button>
        </div>
    </div>
<?php endforeach; ?>
