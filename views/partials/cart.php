<div class="cart-container"> 
    <div class="cart-content">
        <h3>Your Cart</h3>
        <hr>
        <div id="cart-items-container">
            <?php if (empty($items)): ?>
                <p style="text-align:center; color:#888;">Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                <div class="item">
                    <img src="/itdbadm-mp/public/common/coffee-bag.png" class="cart-item-img" />
                    
                    <div class="cart-info">
                        <h5><?= htmlspecialchars($item['bean']['bean_name']) ?></h5>
                        <p class="price">PHP <?= htmlspecialchars($item['bean']['price_per_kg']) ?></p>
                        <div class="qty-row">
                            <span>Quantity:</span>
                            <div class="qty-selector">
                                <button onclick="updateCart(<?= $item['bean']['bean_id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button> 
                                <span><?= htmlspecialchars($item['quantity']) ?></span> 
                                <button onclick="updateCart(<?= $item['bean']['bean_id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                            </div>
                        </div>
                        <p class="item-total">
                            Total: PHP <?= number_format($item['bean']['price_per_kg'] * $item['quantity'], 2) ?>
                        </p>
                    </div>
                    <button class="delete-btn" onclick="removeFromCart(<?= $item['bean']['bean_id'] ?>)">
                        <img src="/itdbadm-mp/public/common/bin.png">
                    </button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="cart-footer">
            <hr>
            <div class="subtotal-row">
                <span class="label">Subtotal:</span>
                <span class="amount" id="cart-total">PHP <?= number_format($total_price ?? 0, 2) ?></span>
            </div>
            <button class="checkout-btn" onclick="location.href='/itdbadm-mp/views/checkout.php'">
                Checkout
            </button>
        </div>
    </div>
</div>