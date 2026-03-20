<?php
    session_start();
    require_once '../config/db-connect.php';

    // redirect if not admin
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
        header('Location: /itdbadm-mp/coffee-backend/index.php');
        exit;
    }

    // --- SEARCH ---
    $bean_search     = $_GET['bean_search'] ?? '';
    $user_search     = $_GET['user_search'] ?? '';
    $supplier_search = $_GET['supplier_search'] ?? '';

    // bean search clause
    $bean_search_clause = '';
    if (!empty($bean_search)) {
        $words = explode(' ', trim($bean_search));
        $conditions = [];
        foreach ($words as $word) {
            $word = mysqli_real_escape_string($conn, $word);
            $conditions[] = "(cb.bean_name LIKE '%$word%' OR cb.variety LIKE '%$word%' OR p.province_name LIKE '%$word%' OR cb.roast_level LIKE '%$word%')";
        }
        $bean_search_clause = 'AND ' . implode(' AND ', $conditions);
    }

    // user search clause
    $user_search_clause = '';
    if (!empty($user_search)) {
        $word = mysqli_real_escape_string($conn, $user_search);
        $user_search_clause = "AND (username LIKE '%$word%' OR role LIKE '%$word%')";
    }

    // supplier search clause
    $supplier_search_clause = '';
    if (!empty($supplier_search)) {
        $word = mysqli_real_escape_string($conn, $supplier_search);
        $supplier_search_clause = "AND (supplier_name LIKE '%$word%' OR email LIKE '%$word%' OR address LIKE '%$word%')";
    }

    // --- BEAN SORT + FILTER ---
    $sort = $_GET['sort'] ?? 'A-Z';
    switch ($sort) {
        case 'Z-A':       $order = "cb.bean_name DESC"; break;
        case 'low-high':  $order = "cb.price_per_kg ASC"; break;
        case 'high-low':  $order = "cb.price_per_kg DESC"; break;
        default:          $order = "cb.bean_name ASC"; break;
    }
    $filter = $_GET['filter'] ?? 'all';
    if ($filter === 'variety')             $filter_clause = "ORDER BY cb.variety ASC";
    elseif ($filter === 'origin_province') $filter_clause = "ORDER BY p.province_name ASC";
    elseif ($filter === 'roast_level')     $filter_clause = "ORDER BY cb.roast_level ASC";
    else                                   $filter_clause = "ORDER BY $order";

    $bean_query = "SELECT cb.*, p.province_name 
                   FROM coffee_bean cb
                   JOIN province p ON cb.origin_province_id = p.province_id
                   WHERE 1=1 $bean_search_clause
                   $filter_clause";
    $result_bean = mysqli_query($conn, $bean_query);

    // --- USER SORT ---
    $user_sort  = $_GET['user_sort'] ?? 'A-Z';
    $user_order = $user_sort == 'Z-A' ? "username DESC" : "username ASC";
    $result_user = mysqli_query($conn, "SELECT * FROM users WHERE 1=1 $user_search_clause ORDER BY $user_order");

    // --- SUPPLIER SORT ---
    $supplier_sort  = $_GET['supplier_sort'] ?? 'A-Z';
    $supplier_order = $supplier_sort == 'Z-A' ? "supplier_name DESC" : "supplier_name ASC";
    $result_supplier = mysqli_query($conn, "SELECT * FROM supplier WHERE 1=1 $supplier_search_clause ORDER BY $supplier_order");

    // --- ORDER LOGS ---
    $order_sort   = $_GET['order_sort'] ?? 'newest';
    $order_status = $_GET['order_status'] ?? 'all';

    $order_where = "WHERE 1=1";
    if ($order_status !== 'all') {
        $order_status_escaped = mysqli_real_escape_string($conn, $order_status);
        $order_where .= " AND sp.payment_status = '$order_status_escaped'";
    }

    $order_clause = $order_sort == 'oldest' ? "ORDER BY sp.payment_date ASC" : "ORDER BY sp.payment_date DESC";

        $result_orderlogs = mysqli_query($conn, "
            SELECT 
                sp.payment_id,
                sp.sale_id,
                sp.payment_date,
                sp.amount_paid,
                sp.currency_code,
                sp.payment_method,
                sp.payment_status,
                CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
                c.email,
                st.store_name,
                s.sale_date
            FROM sale_payment sp
            JOIN sale s ON sp.sale_id = s.sale_id
            JOIN customer c ON s.customer_id = c.customer_id
            JOIN store st ON s.store_id = st.store_id
            $order_where
            $order_clause
    ");

    // keep track of active tab after sort/filter
    $active_tab = $_GET['tab'] ?? 'product-management';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Management</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Notable&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/itdbadm-mp/public/common/style.css">
    </head>
    <body>
        <!-- NAVIGATION -->
        <header>
            <nav>
                <select>
                    <option>Manila Branch</option>
                    <option>Laguna Branch</option>
                </select>
                <img src="/itdbadm-mp/public/common/logo.png" class="logo" alt="Cool Beans Logo" />
                <div class="user-dropdown">
                    <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="#"><img src="/itdbadm-mp/public/common/user.png" class="icons" /></a>
                    <div class="dropdown-menu">
                        <a href="/itdbadm-mp/coffee-backend/index.php">Home</a>
                        <a href="/itdbadm-mp/coffee-backend/auth/logout.php">Logout</a>
                    </div>
                </div>
            </nav>
        </header>

        <div class="breadcrumb"></div>
        <h2>Management</h2>

        <div class="management-options">
            <button class="nav-btn" data-target="product-management">Manage Products</button>
            <button class="nav-btn" data-target="user-management">Manage Users</button>
            <button class="nav-btn" data-target="supplier-management">Manage Suppliers</button>
            <button class="nav-btn" data-target="order-management">View Order Log</button>
        </div>

        <div class="table-container">
            <div class="heading">
                <h3>Products</h3>
            </div>

            <!-- PRODUCT MANAGEMENT -->
            <div id="product-management" class="product-management">
                <div class="tab-controls">
                    <button onclick="location.href='../beans/add_bean.php'">+ Add New</button>
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="product-management">
                        <input type="text" name="bean_search" placeholder="Search beans..."
                            value="<?= htmlspecialchars($bean_search) ?>"
                            style="border:1px solid #3A5635; border-radius:20px; padding:6px 14px; font-family:Montserrat,sans-serif; font-size:13px; color:#5D372A; background:#F4E2D0;">
                        <?php if (!empty($bean_search)): ?>
                            <a href="management.php?tab=product-management" style="color:#EA672D; font-size:13px; font-weight:600;">✕ Clear</a>
                        <?php endif; ?>
                        <label>Filter By:</label>
                        <select name="filter" onchange="this.form.submit()">
                            <option value="all"             <?= $filter == 'all'             ? 'selected' : '' ?>>All Items</option>
                            <option value="variety"         <?= $filter == 'variety'         ? 'selected' : '' ?>>Variety</option>
                            <option value="origin_province" <?= $filter == 'origin_province' ? 'selected' : '' ?>>Origin Province</option>
                            <option value="roast_level"     <?= $filter == 'roast_level'     ? 'selected' : '' ?>>Roast Level</option>
                        </select>
                        <label>Sort By:</label>
                        <select name="sort" onchange="this.form.submit()">
                            <option value="A-Z"      <?= $sort == 'A-Z'      ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A"      <?= $sort == 'Z-A'      ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                            <option value="low-high" <?= $sort == 'low-high' ? 'selected' : '' ?>>Price, Low-High</option>
                            <option value="high-low" <?= $sort == 'high-low' ? 'selected' : '' ?>>Price, High-Low</option>
                        </select>
                        <button type="submit">Search</button>
                    </form>
                </div>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Variety</th>
                        <th>Province</th>
                        <th>Roast Level</th>
                        <th>Price/kg</th>
                        <th>Actions</th>
                    </tr>
                    <?php if (mysqli_num_rows($result_bean) == 0): ?>
                        <tr><td colspan="7" style="text-align:center; padding:30px; color:#888;">No beans found.</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result_bean)): ?>
                        <tr>
                            <td><?= $row['bean_id'] ?></td>
                            <td><?= $row['bean_name'] ?></td>
                            <td><?= $row['variety'] ?></td>
                            <td><?= $row['province_name'] ?></td>
                            <td><?= $row['roast_level'] ?></td>
                            <td>P<?= number_format($row['price_per_kg'], 2) ?></td>
                            <td>
                                <a href="../beans/update_bean.php?id=<?= $row['bean_id'] ?>">Edit</a>
                                <a href="../beans/delete_bean.php?id=<?= $row['bean_id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this bean?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- USER MANAGEMENT -->
            <div id="user-management" class="user-management">
                <div class="tab-controls">
                    <button onclick="location.href='../users/add_user.php'">+ Add New</button>
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="user-management">
                        <input type="text" name="user_search" placeholder="Search users..."
                            value="<?= htmlspecialchars($user_search) ?>"
                            style="border:1px solid #3A5635; border-radius:20px; padding:6px 14px; font-family:Montserrat,sans-serif; font-size:13px; color:#5D372A; background:#F4E2D0;">
                        <?php if (!empty($user_search)): ?>
                            <a href="management.php?tab=user-management" style="color:#EA672D; font-size:13px; font-weight:600;">✕ Clear</a>
                        <?php endif; ?>
                        <label>Sort By:</label>
                        <select name="user_sort" onchange="this.form.submit()">
                            <option value="A-Z" <?= $user_sort == 'A-Z' ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A" <?= $user_sort == 'Z-A' ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                        </select>
                        <button type="submit">Search</button>
                    </form>
                </div>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Is Active</th>
                        <th>Actions</th>
                    </tr>
                    <?php if (mysqli_num_rows($result_user) == 0): ?>
                        <tr><td colspan="5" style="text-align:center; padding:30px; color:#888;">No users found.</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result_user)): ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= $row['username'] ?></td>
                            <td><?= $row['role'] ?></td>
                            <td><?= $row['is_active'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <a href="../users/edit_user.php?id=<?= $row['user_id'] ?>">Edit</a>
                                <a href="../users/delete_user.php?id=<?= $row['user_id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- SUPPLIER MANAGEMENT -->
            <div id="supplier-management" class="supplier-management">
                <div class="tab-controls">
                    <button onclick="location.href='../suppliers/add_suppliers.php'">+ Add New</button>
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="supplier-management">
                        <input type="text" name="supplier_search" placeholder="Search suppliers..."
                            value="<?= htmlspecialchars($supplier_search) ?>"
                            style="border:1px solid #3A5635; border-radius:20px; padding:6px 14px; font-family:Montserrat,sans-serif; font-size:13px; color:#5D372A; background:#F4E2D0;">
                        <?php if (!empty($supplier_search)): ?>
                            <a href="management.php?tab=supplier-management" style="color:#EA672D; font-size:13px; font-weight:600;">✕ Clear</a>
                        <?php endif; ?>
                        <label>Sort By:</label>
                        <select name="supplier_sort" onchange="this.form.submit()">
                            <option value="A-Z" <?= $supplier_sort == 'A-Z' ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A" <?= $supplier_sort == 'Z-A' ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                        </select>
                        <button type="submit">Search</button>
                    </form>
                </div>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                    <?php if (mysqli_num_rows($result_supplier) == 0): ?>
                        <tr><td colspan="7" style="text-align:center; padding:30px; color:#888;">No suppliers found.</td></tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result_supplier)): ?>
                        <tr>
                            <td><?= $row['supplier_id'] ?></td>
                            <td><?= $row['supplier_name'] ?></td>
                            <td><?= $row['contact_number'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['address'] ?></td>
                            <td><?= $row['city_id'] ?></td>
                            <td>
                                <a href="../suppliers/edit_suppliers.php?id=<?= $row['supplier_id'] ?>">Edit</a>
                                <a href="../suppliers/delete_suppliers.php?id=<?= $row['supplier_id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </table>
            </div>

            <!-- ORDER MANAGEMENT -->
            <div id="order-management" class="order-management">
                <div class="tab-controls">
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="order-management">
                        <label>Status:</label>
                        <select name="order_status" onchange="this.form.submit()">
                            <option value="all"     <?= ($order_status ?? 'all') == 'all'     ? 'selected' : '' ?>>All</option>
                            <option value="PENDING" <?= ($order_status ?? '') == 'PENDING'    ? 'selected' : '' ?>>Pending</option>
                            <option value="PAID"    <?= ($order_status ?? '') == 'PAID'       ? 'selected' : '' ?>>Paid</option>
                            <option value="FAILED"  <?= ($order_status ?? '') == 'FAILED'     ? 'selected' : '' ?>>Failed</option>
                        </select>
                        <label>Sort By:</label>
                        <select name="order_sort" onchange="this.form.submit()">
                            <option value="newest" <?= ($order_sort ?? 'newest') == 'newest' ? 'selected' : '' ?>>Newest First</option>
                            <option value="oldest" <?= ($order_sort ?? '') == 'oldest'       ? 'selected' : '' ?>>Oldest First</option>
                        </select>
                    </form>
                </div>
                <table class="viewrecords">
                    <tr>
                        <th>Payment ID</th>
                        <th>Customer</th>
                        <th>Store</th>
                        <th>Sale Date</th>
                        <th>Total Amount</th>
                        <th>Currency</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php if (mysqli_num_rows($result_orderlogs) == 0): ?>
                        <tr>
                            <td colspan="9" style="text-align:center; padding:30px; color:#888;">No orders yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php while ($row = mysqli_fetch_assoc($result_orderlogs)): ?>
                        <tr>
                            <td><?= $row['payment_id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row['customer_name']) ?></strong><br>
                                <small><?= htmlspecialchars($row['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['store_name']) ?></td>
                            <td><?= $row['sale_date'] ?></td>
                            <td>P<?= number_format($row['amount_paid'] ?? $row['total_amount'] ?? 0, 2) ?></td>
                            <td><?= $row['currency_code'] ?></td>
                            <td><?= $row['payment_method'] ?></td>
                            <td>
                                <?php $status = $row['payment_status'] ?? 'UNKNOWN'; ?>
                                <span class="status-badge status-<?= strtolower($status) ?>"
                                    data-id="<?= $row['payment_id'] ?>"
                                    onclick="cycleStatus(this)">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td>
                                <a href="#" onclick="viewOrder(<?= $row['sale_id'] ?>); return false;">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- ORDER MODAL -->
        <div id="order-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000; justify-content:center; align-items:center;">
            <div style="background:#fff8f3; border-radius:16px; padding:40px; width:600px; max-height:80vh; overflow-y:auto; position:relative;">
                <button onclick="closeOrderModal()" style="position:absolute; top:15px; right:20px; background:none; border:none; font-size:20px; cursor:pointer; color:#5D372A;">✕</button>
                <h3 style="color:#5D372A; font-family:Notable,sans-serif; margin-bottom:20px;">Order Details</h3>
                <div id="order-modal-content">Loading...</div>
            </div>
        </div>
    </body>

    <script>
        const navButtons = document.querySelectorAll('.nav-btn');
        const sections = document.querySelectorAll('.table-container > div[id]');
        const mainHeading = document.querySelector('.heading h3');

        function showTab(targetId) {
            navButtons.forEach(btn => btn.classList.remove('active-btn'));
            sections.forEach(section => section.style.display = 'none');
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = 'block';
                const btn = document.querySelector(`[data-target="${targetId}"]`);
                if (btn) {
                    btn.classList.add('active-btn');
                    mainHeading.innerText = btn.innerText.replace('Manage ', '');
                }
            }
        }

        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                if (targetId) {
                    document.querySelectorAll('.tab-controls input[type="text"]').forEach(input => {
                        input.value = '';
                    });
                    showTab(targetId);
                }
            });
        });

        // restore active tab after sort/filter
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = '<?= $active_tab ?>';
            showTab(activeTab);
        });

        // UPDATE ORDER STATUS
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', async function() {
                const paymentId = this.dataset.id;
                const status    = this.value;

                const formData = new FormData();
                formData.append('payment_id', paymentId);
                formData.append('status', status);

                const res  = await fetch('../orders/update_status.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();

                if (data.success) {
                    this.style.borderColor = status == 'PAID' ? '#3A5635' : status == 'FAILED' ? '#EA672D' : '#888';
                    this.style.color       = status == 'PAID' ? '#3A5635' : status == 'FAILED' ? '#EA672D' : '#888';
                } else {
                    alert('Failed to update status.');
                }
            });
        });
                    async function cycleStatus(badge) {
                    const order = ['PENDING', 'PAID', 'FAILED'];
                    const current = badge.textContent.trim();
                    const next = order[(order.indexOf(current) + 1) % order.length];

                    const formData = new FormData();
                    formData.append('payment_id', badge.dataset.id);
                    formData.append('status', next);

                    const res  = await fetch('../orders/update_status.php', { method: 'POST', body: formData });
                    const data = await res.json();

                    if (data.success) {
                        badge.textContent = next;
                        badge.className = `status-badge status-${next.toLowerCase()}`;
                    } else {
                        alert('Failed to update status.');
                    }
                }

            async function viewOrder(saleId) {
            const modal   = document.getElementById('order-modal');
            const content = document.getElementById('order-modal-content');
            modal.style.display = 'flex';
            content.innerHTML   = 'Loading...';

            const res  = await fetch(`../orders/get_order.php?sale_id=${saleId}`);
            const data = await res.json();

            if (!data.success) {
                content.innerHTML = 'Failed to load order.';
                return;
            }

            const s = data.sale;
            const itemsHTML = data.items.map(item => `
                <tr>
                    <td>${item.bean_name}</td>
                    <td>${item.quantity} kg</td>
                    <td>P${parseFloat(item.unit_price).toFixed(2)}</td>
                    <td>P${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
            `).join('');

            content.innerHTML = `
                <table style="width:100%; border-spacing:0; margin-bottom:20px;">
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Customer</td><td>${s.customer_name}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Email</td><td>${s.email}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Contact</td><td>${s.contact_number}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Address</td><td>${s.address}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Store</td><td>${s.store_name}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Sale Date</td><td>${s.sale_date}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Payment Method</td><td>${s.payment_method ?? 'N/A'}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Payment Status</td><td>${s.payment_status ?? 'N/A'}</td></tr>
                    <tr><td style="font-weight:600; padding:4px 0; color:#3A5635;">Total Amount</td><td>P${parseFloat(s.total_amount).toFixed(2)}</td></tr>
                </table>

                <h4 style="color:#5D372A; margin-bottom:10px;">Items Ordered</h4>
                <table class="viewrecords">
                    <tr>
                        <th>Bean</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                    ${itemsHTML}
                    <tr>
                        <td colspan="3" style="font-weight:bold; text-align:right;">Total:</td>
                        <td style="font-weight:bold;">P${parseFloat(s.total_amount).toFixed(2)}</td>
                    </tr>
                </table>

                <div style="margin-top:24px; text-align:right;">
                    <button onclick="cancelOrder(${s.sale_id})"
                        style="background-color:#EA672D; color:white; border:none; border-radius:20px; padding:10px 24px;
                            font-family:Montserrat,sans-serif; font-weight:600; font-size:13px; cursor:pointer;">
                        Cancel Order
                    </button>
                </div>
            `;
        }

        function closeOrderModal() {
            document.getElementById('order-modal').style.display = 'none';
        }
                document.getElementById('order-modal').addEventListener('click', function(e) {
                    if (e.target === this) closeOrderModal();
                });
                async function cancelOrder(saleId) {
            if (!confirm('Are you sure you want to cancel this order? This cannot be undone.')) return;

            const formData = new FormData();
            formData.append('sale_id', saleId);

            const res  = await fetch('../orders/cancel_order.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                alert('Order cancelled successfully.');
                closeOrderModal();
                location.reload(); 
            } else {
                alert('Failed to cancel order: ' + data.message);
            }
        }
    </script>
</html>
<?php mysqli_close($conn); ?>