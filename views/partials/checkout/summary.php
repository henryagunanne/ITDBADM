<div class="order-summary">
    <h5>Order Summary</h5>

    <div class="orders">
        <?php if (empty($items)): ?>
            <p style="color:#888; text-align:center;">Your cart is empty.</p>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
            <div class="item">
                <img src="/itdbadm-mp/public/common/coffee-bag.png" class="cart-item-img" />
                <div class="cart-info">
                    <h5><?= htmlspecialchars($item['bean']['bean_name']) ?></h5>
                    <p class="price">PHP <?= number_format($item['bean']['price_per_kg'], 2) ?></p>
                    <div class="qty-row">
                        <span>Quantity:</span>
                        <div><?= htmlspecialchars($item['quantity']) ?></div>
                    </div>
                    <p class="item-total">
                        Total: PHP <?= number_format($item['bean']['price_per_kg'] * $item['quantity'], 2) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <hr style="color: #5D372A;">

    <div class="payment-summary">
        <h5>Payment Summary</h5>
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align:right;">PHP <?= number_format($total_price, 2) ?></td>
            </tr>
            <tr>
                <td>Estimated Shipping:</td>
                <td style="text-align:right;">PHP 0.00</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Grand Total:</td>
                <td style="text-align:right; font-weight:bold;">PHP <?= number_format($total_price, 2) ?></td>
            </tr>
        </table>
    </div>
</div>