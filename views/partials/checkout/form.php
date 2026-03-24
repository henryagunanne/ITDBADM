<form method="POST" action="/itdbadm-mp/coffee-backend/orders/process_sale.php" id="checkout-form">
    <div class="contact-info">
        <div class="email">
            <label>Email Address</label>
            <input type="text" name="email" placeholder="Email Address">
            <div class="checkout-error" id="err-email">Email is required.</div>
        </div>

        <div class="store">
            <label>Store</label>
            <select name="store_id">
                <?php foreach ($stores as $store): ?>
                    <option value="<?= htmlspecialchars($store['store_id']) ?>">
                        <?= htmlspecialchars($store['store_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="shipping-address">
            <label>Shipping Address</label>
            <select name="country">
                <option value="Philippines">Philippines</option>
                <option value="Japan">Japan</option>
                <option value="USA">United States of America</option>
            </select>
            <div class="name">
                <input type="text" name="first_name" placeholder="First Name">
                <input type="text" name="last_name" placeholder="Last Name">
            </div>
            <div class="checkout-error" id="err-name">First name is required.</div>
            <input type="text" name="address" placeholder="Address">
            <div class="address-line2">
                <input type="number" name="postal_code" placeholder="Postal Code">
                <select name="city_id">
                    <?php foreach ($provinces as $prov): ?>
                        <option value="<?= htmlspecialchars($prov['province_id']) ?>">
                            <?= htmlspecialchars($prov['province_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="text" name="contact_number" placeholder="Contact Number">
        </div>

        <div class="payment">
            <div class="payment-method">
                <label>Payment Method</label>
                <table>
                    <tr><td><input type="radio" name="payment_method" value="CASH" checked> Cash on Delivery</td></tr>
                    <tr><td><input type="radio" name="payment_method" value="CARD"> Credit / Debit Card</td></tr>
                    <tr><td><input type="radio" name="payment_method" value="BANK TRANSFER"> Bank Transfer</td></tr>
                    <tr><td><input type="radio" name="payment_method" value="CHEQUE"> Cheque</td></tr>
                </table>
            </div>

            <div class="payment-details" id="card-details">
                <label>Card Details</label>
                <table>
                    <tr>
                        <td><input type="text" name="card_number" placeholder="Card Number" id="card_number"></td>
                        <td><input type="text" name="expiry" placeholder="MM/YY" id="expiry"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="card_holder" placeholder="Card Holder Name" id="card_holder"></td>
                        <td><input type="text" name="card_verification" placeholder="CVV" id="cvv"></td>
                    </tr>
                </table>
                <div class="checkout-error" id="err-card">Please fill in all card details.</div>
            </div>
        </div>

        <input type="hidden" name="currency_code" value="PHP">
        <div class="checkout-error" id="err-general"></div>
        <input type="submit" value="Place Order" class="place-order-btn">
    </div>
</form>