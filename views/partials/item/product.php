<div class="product-container">
    <div class="left-panel">
        <img src="/itdbadm-mp/public/common/coffee-bag.png">
    </div>

    <div class="right-panel">
        <div class="description">
            <h3><?= htmlspecialchars($selected['bean_name']) ?></h3>
            <pre><?= htmlspecialchars($selected['description']) ?></pre>

            <table>
                <tr>
                    <td style="font-weight: bold;">Variety:</td>
                    <td><?= htmlspecialchars($selected['variety']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Origin Province:</td>
                    <td><?= htmlspecialchars($selected['province_name']) ?></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Roast Level:</td>
                    <td><?= htmlspecialchars($selected['roast_level']) ?></td>
                </tr>
            </table>

            <h4>PHP <?= htmlspecialchars($selected['price_per_kg']) ?>/kg</h4>
        </div>

        <div class="option">
            <div class="controls">
                <div class="qty-selector">
                    <button class="qty-btn minus" data-id="<?= $selected['bean_id'] ?>">-</button>
                    <span class="qty-display" id="qty-<?= $selected['bean_id'] ?>">1</span>
                    <button class="qty-btn plus" data-id="<?= $selected['bean_id'] ?>">+</button>
                </div>
                <button class="add-btn" data-id="<?= $selected['bean_id'] ?>" onclick="addToCart(<?= $selected['bean_id'] ?>)">Add to Cart</button>
            </div>

            <div id="add-notif" style="display:none; margin-top:10px; color:#3A5635; font-weight:600;">
                ✓ Added to cart!
            </div>
        </div>
    </div>
</div>