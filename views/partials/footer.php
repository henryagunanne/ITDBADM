<footer>
  <img src="../public/common/logo.png" />
  
  <table>
    <tr>
      <td><a href="">About Us</a></td>
      <td><a href="">Terms & Conditions</a></td>
    </tr>
    <tr>
      <td><a href="">Contact Us</a></td>
      <td><a href="">How to Order</a></td>
    </tr>
  </table>
</footer>

<script>
    const showPopupCart = document.querySelector('.cart-icon');
    const popupContainerCart = document.querySelector('.cart-container');
    const showPopupLogin = document.querySelector('.login-icon');
    const popupContainerLogin = document.querySelector('.login-container');

    showPopupCart.onclick = (e) => {
        e.preventDefault();
        popupContainerCart.classList.toggle('active'); 
    };

    showPopupLogin.onclick = (e) => {
        e.preventDefault();
        popupContainerLogin.classList.toggle('active'); 
    };

    window.onclick = (e) => {
        if (!popupContainerCart.contains(e.target) && !showPopupCart.contains(e.target)) {
            popupContainerCart.classList.remove('active');
        }
        else if (!popupContainerLogin.contains(e.target) && !showPopupLogin.contains(e.target)) {
            popupContainerLogin.classList.remove('active');
        }
    };
// QTY BUTTONS
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const id      = this.dataset.id;
        const display = document.getElementById('qty-' + id);
        let qty       = parseInt(display.textContent);

        if (this.classList.contains('plus')) {
            qty++;
        } else if (this.classList.contains('minus') && qty > 1) {
            qty--;
        }

        display.textContent = qty;
    });
});

// ADD TO CART
document.querySelectorAll('.add-btn').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.preventDefault();
        const id      = this.dataset.id;
        const qty     = parseInt(document.getElementById('qty-' + id).textContent);

        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('bean_id', id);
        formData.append('qty', qty);

        const res  = await fetch('/itdbadm-mp/coffee-backend/cart/update_cart.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            updateCartUI(data);
            // flash feedback
            this.textContent = '✓ Added!';
            setTimeout(() => this.textContent = 'Add to Cart', 1500);
        }
    });
});

  // UPDATE CART UI
  function updateCartUI(data) {
      // update cart items display
      const cartContent = document.querySelector('.cart-content');
      if (!cartContent) return;

      // rebuild cart items
      let itemsHTML = '<h3>Your Cart</h3><hr>';

      if (data.cart.length === 0) {
          itemsHTML += '<p style="text-align:center; color:#888;">Your cart is empty.</p>';
      } else {
          data.cart.forEach(item => {
              itemsHTML += `
                  <div class="item">
                      <img src="/itdbadm-mp/public/common/coffee-bag.png" class="cart-item-img" />
                      <div class="cart-info">
                          <h5>${item.bean.bean_name}</h5>
                          <p class="price">PHP ${item.bean.price_per_kg}</p>
                          <div class="qty-row">
                              <span>Quantity:</span>
                              <div class="qty-selector">
                                  <button onclick="cartUpdate(${item.bean.bean_id}, ${item.quantity - 1})">-</button>
                                  <span>${item.quantity}</span>
                                  <button onclick="cartUpdate(${item.bean.bean_id}, ${item.quantity + 1})">+</button>
                              </div>
                          </div>
                          <p class="item-total">Total: PHP ${(item.bean.price_per_kg * item.quantity).toFixed(2)}</p>
                      </div>
                      <button class="delete-btn" onclick="cartRemove(${item.bean.bean_id})">
                          <img src="/itdbadm-mp/public/common/bin.png">
                      </button>
                  </div>
              `;
          });
      }

      itemsHTML += `
          <div class="cart-footer">
              <hr>
              <div class="subtotal-row">
                  <span class="label">Subtotal:</span>
                  <span class="amount">PHP ${data.total}</span>
              </div>
              <button class="checkout-btn" onclick="location.href='/itdbadm-mp/views/checkout.php'">Checkout</button>
          </div>
      `;

      cartContent.innerHTML = itemsHTML;
  }

  // UPDATE QTY IN CART
  async function cartUpdate(bean_id, qty) {
      const formData = new FormData();
      formData.append('action', 'update');
      formData.append('bean_id', bean_id);
      formData.append('qty', qty);

      const res  = await fetch('/itdbadm-mp/coffee-backend/cart/update_cart.php', { method: 'POST', body: formData });
      const data = await res.json();
      if (data.success) updateCartUI(data);
  }

  // REMOVE FROM CART
  async function cartRemove(bean_id) {
      const formData = new FormData();
      formData.append('action', 'remove');
      formData.append('bean_id', bean_id);

      const res  = await fetch('/itdbadm-mp/coffee-backend/cart/update_cart.php', { method: 'POST', body: formData });
      const data = await res.json();
      if (data.success) updateCartUI(data);
  }    

  // render cart from session data
async function refreshCart() {
    const res = await fetch('/itdbadm-mp/coffee-backend/cart/update.php', {
        method: 'POST',
        body: (() => { const f = new FormData(); f.append('action', 'get'); return f; })()
    });
    const data = await res.json();
    if (!data.success) return;

    const container = document.getElementById('cart-items-container');
    if (!container) return;

    if (data.cart.length === 0) {
        container.innerHTML = '<p style="text-align:center; color:#888;">Your cart is empty.</p>';
        document.getElementById('cart-total').textContent = 'PHP 0.00';
        return;
    }

    container.innerHTML = data.cart.map(item => `
        <div class="item">
            <img src="/itdbadm-mp/public/common/coffee-bag.png" class="cart-item-img" />
            <div class="cart-info">
                <h5>${item.bean.bean_name}</h5>
                <p class="price">PHP ${item.bean.price_per_kg}</p>
                <div class="qty-row">
                    <span>Quantity:</span>
                    <div class="qty-selector">
                        <button onclick="updateCart(${item.bean.bean_id}, ${item.quantity - 1})">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="updateCart(${item.bean.bean_id}, ${item.quantity + 1})">+</button>
                    </div>
                </div>
                <p class="item-total">Total: PHP ${(item.bean.price_per_kg * item.quantity).toFixed(2)}</p>
            </div>
            <button class="delete-btn" onclick="removeFromCart(${item.bean.bean_id})">
                <img src="/itdbadm-mp/public/common/bin.png">
            </button>
        </div>
    `).join('');

    document.getElementById('cart-total').textContent = 'PHP ' + data.total;
}

async function addToCart(beanId, qty = 1) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('bean_id', beanId);
    formData.append('qty', qty);

    const res = await fetch('/itdbadm-mp/coffee-backend/cart/update.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    if (data.success) refreshCart();
}

async function updateCart(beanId, qty) {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('bean_id', beanId);
    formData.append('qty', qty);

    const res = await fetch('/itdbadm-mp/coffee-backend/cart/update.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    if (data.success) refreshCart();
}

async function removeFromCart(beanId) {
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('bean_id', beanId);

    const res = await fetch('/itdbadm-mp/coffee-backend/cart/update.php', {
        method: 'POST',
        body: formData
    });
    const data = await res.json();
    if (data.success) refreshCart();
}

// load cart on page load
document.addEventListener('DOMContentLoaded', refreshCart);
</script>