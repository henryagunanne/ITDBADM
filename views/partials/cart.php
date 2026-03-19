<div class="cart-container"> 
    <div class="cart-content">
        <h3>Your Cart</h3>
        <hr>

        <?php foreach ($items as $item): ?>
        <div class="item">
            <img src="<?= htmlspecialchars($item['bean']['bean_image_path'] ?? '') ?>" class="cart-item-img" />
            
            <div class="cart-info">
                <h5><?= htmlspecialchars($item['bean']['bean_name']) ?></h5>
                <p class="price">PHP <?= htmlspecialchars($item['bean']['price_per_kg']) ?></p>

                <div class="qty-row">
                    <span>Quantity:</span>
                    <div class="qty-selector">
                        <button>-</button> 
                        <span><?= htmlspecialchars($item['quantity']) ?></span> 
                        <button>+</button>
                    </div>
                </div>

                <p class="item-total">
                    Total: PHP <?= number_format($item['bean']['price_per_kg'] * $item['quantity'], 2) ?>
                </p>
            </div>

            <button class="delete-btn">
                <img src="../public/common/bin.png">
            </button>
        </div>
        <?php endforeach; ?>
        
        <div class="cart-footer">
            <hr>
            <div class="subtotal-row">
                <span class="label">Subtotal:</span>
                <span class="amount">PHP <?= number_format($total_price ?? 0, 2) ?></span>
            </div>

            <button class="checkout-btn" onclick="location.href='/checkout'">
                Checkout
            </button>
        </div>
    </div>
</div>