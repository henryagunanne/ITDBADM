<footer>
  <img src="/itdbadm-mp/public/common/logo.png" />
  
  <table>
    <tr>
      <td><a href="/itdbadm-mp/views/about.php">About Us</a></td>
      <td><a href="/itdbadm-mp/views/terms.php">Terms & Conditions</a></td>
    </tr>
    <tr>
      <td><a href="/itdbadm-mp/views/contact.php">Contact Us</a></td>
      <td><a href="/itdbadm-mp/views/how-to-order.php">How to Order</a></td>
    </tr>
  </table>
</footer>

<script>
const CART_URL = '/itdbadm-mp/coffee-backend/cart/update.php';

// --- POPUPS ---
const showPopupCart       = document.querySelector('.cart-icon');
const popupContainerCart  = document.querySelector('.cart-container');
const showPopupLogin      = document.querySelector('.login-icon');
const popupContainerLogin = document.querySelector('.login-container');

if (showPopupCart) {
    showPopupCart.onclick = (e) => {
        e.preventDefault();
        popupContainerCart.classList.toggle('active');
    };
}

if (showPopupLogin) {
    showPopupLogin.onclick = (e) => {
        e.preventDefault();
        popupContainerLogin.classList.toggle('active');
    };
}

window.onclick = (e) => {
    if (popupContainerCart && showPopupCart &&
        !popupContainerCart.contains(e.target) &&
        !showPopupCart.contains(e.target)) {
        popupContainerCart.classList.remove('active');
    }
    if (popupContainerLogin && showPopupLogin &&
        !popupContainerLogin.contains(e.target) &&
        !showPopupLogin.contains(e.target)) {
        popupContainerLogin.classList.remove('active');
    }
};

// --- QTY BUTTONS (product listing) ---
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('qty-btn')) {
        e.preventDefault();
        e.stopPropagation();
        const id    = e.target.dataset.id;
        const span  = document.getElementById('qty-' + id);
        if (!span) return;
        let qty = parseInt(span.textContent);
        if (e.target.classList.contains('plus')) qty++;
        else if (e.target.classList.contains('minus') && qty > 1) qty--;
        span.textContent = qty;
    }
});

// --- ADD TO CART ---
async function addToCart(beanId) {
    const span = document.getElementById('qty-' + beanId);
    const qty  = span ? parseInt(span.textContent) : 1;

    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('bean_id', beanId);
    formData.append('qty', qty);

    const res  = await fetch(CART_URL, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) {
        refreshCart();

        // flash on add-btn
        const btn = document.querySelector(`.add-btn[data-id="${beanId}"]`);
        if (btn) {
            btn.textContent = '✓ Added!';
            setTimeout(() => btn.textContent = 'Add to Cart', 1500);
        }

        // show notif on item page
        const notif = document.getElementById('add-notif');
        if (notif) {
            notif.style.display = 'block';
            setTimeout(() => notif.style.display = 'none', 2000);
        }
    }
}

// --- UPDATE QTY IN CART ---
async function updateCart(beanId, qty) {
    if (qty < 1) return;
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('bean_id', beanId);
    formData.append('qty', qty);

    const res  = await fetch(CART_URL, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) refreshCart();
}

// --- REMOVE FROM CART ---
async function removeFromCart(beanId) {
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('bean_id', beanId);

    const res  = await fetch(CART_URL, { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) refreshCart();
}

// --- REFRESH CART UI ---
async function refreshCart() {
    const formData = new FormData();
    formData.append('action', 'get');

    const res  = await fetch(CART_URL, { method: 'POST', body: formData });
    const data = await res.json();
    if (!data.success) return;

    const container = document.getElementById('cart-items-container');
    if (!container) return;

    if (data.cart.length === 0) {
        container.innerHTML = '<p style="text-align:center; color:#888;">Your cart is empty.</p>';
        const total = document.getElementById('cart-total');
        if (total) total.textContent = 'PHP 0.00';
        return;
    }

    container.innerHTML = data.cart.map(item => `
        <div class="item">
            <img src="/itdbadm-mp/public/common/coffee-bag.png" class="cart-item-img" />
            <div class="cart-info">
                <h5>${item.bean.bean_name}</h5>
                <p class="price">PHP ${parseFloat(item.bean.price_per_kg).toFixed(2)}</p>
                <div class="qty-row">
                    <span>Quantity:</span>
                    <div class="qty-selector">
                        <button
                            ${item.quantity <= 1
                                ? 'disabled style="opacity:0.3; cursor:not-allowed;"'
                                : `onclick='updateCart(${item.bean.bean_id}, ${item.quantity - 1})'`}>-</button>
                        <span>${item.quantity}</span>
                        <button onclick='updateCart(${item.bean.bean_id}, ${item.quantity + 1})'>+</button>
                    </div>
                </div>
                <p class="item-total">Total: PHP ${(item.bean.price_per_kg * item.quantity).toFixed(2)}</p>
            </div>
            <button class='delete-btn' onclick='removeFromCart(${item.bean.bean_id})'>
                <img src='/itdbadm-mp/public/common/bin.png'>
            </button>
        </div>
    `).join('');

    const total = document.getElementById('cart-total');
    if (total) total.textContent = 'PHP ' + data.total;
}

// --- SEARCH BAR ---
const searchToggle = document.querySelector('.search-toggle');
const searchInput  = document.querySelector('.search-input');

if (searchToggle && searchInput) {
    searchToggle.onclick = (e) => {
        e.preventDefault();
        searchInput.classList.toggle('active');
        if (searchInput.classList.contains('active')) searchInput.focus();
    };

    window.addEventListener('click', (e) => {
        if (!e.target.closest('.search-wrapper')) {
            searchInput.classList.remove('active');
        }
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && searchInput.value.trim()) {
            window.location.href = `/itdbadm-mp/views/beans.php?search=${encodeURIComponent(searchInput.value.trim())}`;
        }
    });
}
// --- CHECKOUT FORM ---
// --- CHECKOUT FORM ---
const checkoutForm = document.getElementById('checkout-form');
if (checkoutForm) {

    // show/hide card details based on payment method
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails   = document.getElementById('card-details');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'CARD') {
                cardDetails.classList.add('active');
            } else {
                cardDetails.classList.remove('active');
            }
        });
    });

    // form validation + submit
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // reset errors
        document.querySelectorAll('.checkout-error').forEach(el => el.classList.remove('active'));

        let valid = true;

        // email
        const email = this.querySelector('[name="email"]').value.trim();
        if (!email) {
            document.getElementById('err-email').classList.add('active');
            valid = false;
        }

        // first name
        const firstName = this.querySelector('[name="first_name"]').value.trim();
        if (!firstName) {
            document.getElementById('err-name').classList.add('active');
            valid = false;
        }

        // card details if CARD selected
        const selectedPayment = this.querySelector('input[name="payment_method"]:checked')?.value;
        if (selectedPayment === 'CARD') {
            const cardNumber = document.getElementById('card_number').value.trim();
            const expiry     = document.getElementById('expiry').value.trim();
            const cardHolder = document.getElementById('card_holder').value.trim();
            const cvv        = document.getElementById('cvv').value.trim();

            if (!cardNumber || !expiry || !cardHolder || !cvv) {
                document.getElementById('err-card').classList.add('active');
                valid = false;
            }
        }

        if (!valid) return;

        // submit
        const formData = new FormData(this);
        const res  = await fetch('/itdbadm-mp/coffee-backend/checkout/process_sale.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            alert('Order placed successfully!');
            window.location.href = '/itdbadm-mp/coffee-backend/index.php';
        } else {
            const errGeneral = document.getElementById('err-general');
            errGeneral.textContent = data.message;
            errGeneral.classList.add('active');
        }
    });
}
// --- LOAD CART ON PAGE LOAD ---
document.addEventListener('DOMContentLoaded', refreshCart);
</script>