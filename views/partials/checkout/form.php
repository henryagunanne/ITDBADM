<form method="POST" action="">
    <div class="contact-info">
        <div class="email">
            <label>Contact</label>
            <input type="text" name="email-add" placeholder="Email Address">
        </div>

        <div class="store">
            <label>Store</label>
            <select name="store">
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
                <option value="Japan">Japan</option>
                <option value="Philippines">Philippines</option>
                <option value="USA">United States of America</option>
            </select>
            <div class="name">
                <input type="text" name="first-name" placeholder="First Name" size="35">
                <input type="text" name="last-name" placeholder="Last Name" size="35">
            </div>
            <input type="text" name="address" placeholder="Address">
            <div class="address-line2">
                <input type="number" name="postal-code" placeholder="Postal Code" style="width: 250px;">
                <select name="city" style="width: 260px;">
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= htmlspecialchars($city['city_id']) ?>">
                            <?= htmlspecialchars($city['city_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="number" name="contact-number" placeholder="Contact Number">
        </div>

        <div class="payment">
            <div class="payment-method">
                <label>Payment Method</label>
                <table>
                    <tr><td><input type="radio" name="payment-method" value="COD"> Cash on Delivery</td></tr>
                    <tr><td><input type="radio" name="payment-method" value="card"> Credit / Debit Card</td></tr>
                    <tr><td><input type="radio" name="payment-method" value="e-wallet"> E-wallet</td></tr>
                </table>
            </div>

            <div class="payment-details">
                <label>Payment Details</label>
                <table>
                    <tr>
                        <td><input type="number" name="card-number" placeholder="Card Number"></td>
                        <td><input type="text" name="expiry" placeholder="MM/YY"></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="card-holder" placeholder="Card Holder Name"></td>
                        <td><input type="number" name="card-verification" placeholder="CVV"></td>
                    </tr>
                </table>
            </div>
        </div>

        <input type="submit" value="Place Order" class="apply-btn" style="align-self: center;">
    </div>
</form>