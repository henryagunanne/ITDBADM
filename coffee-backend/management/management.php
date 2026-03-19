<!-- <?php
    // require_once '../coffee-backend/config/db-connect.php';
    // $bean_query = "SELECT * FROM coffee_bean cb JOIN province p ON cb.origin_province_id=p.province_id;";
    // $result_bean = mysqli_query($conn, $bean_query);
    // $user_query = "SELECT * FROM users";
    // $result_user = mysqli_query($conn, $user_query);
    // $supplier_query = "SELECT * FROM supplier";
    // $result_supplier = mysqli_query($conn, $supplier_query);
?> -->

<?php
    session_start();
    require_once '../config/db-connect.php';

    // bean sort + filter
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
                   $filter_clause";
    $result_bean = mysqli_query($conn, $bean_query);

    // user sort
    $user_sort  = $_GET['user_sort'] ?? 'A-Z';
    $user_order = $user_sort == 'Z-A' ? "username DESC" : "username ASC";
    $result_user = mysqli_query($conn, "SELECT * FROM users ORDER BY $user_order");

    // supplier sort
    $supplier_sort  = $_GET['supplier_sort'] ?? 'A-Z';
    $supplier_order = $supplier_sort == 'Z-A' ? "supplier_name DESC" : "supplier_name ASC";
    $result_supplier = mysqli_query($conn, "SELECT * FROM supplier ORDER BY $supplier_order");
    $result_orderlogs = mysqli_query($conn, "SELECT * FROM sale_payment ORDER BY payment_date DESC");

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
        <link rel="stylesheet" href="../../public/common/style_mgmt.css">
    </head>
    <body>
        <!-- NAVIGATION -->
        <header>
            <nav>
                <select>
                    <option>Manila Branch</option>
                    <option>Laguna Branch</option>
                </select>
                <img src="../../public/common/logo.png" class="logo" alt="Cool Beans Logo" />
                <a href=""><img src="../../public/common/user.png" class="icons" /></a>
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
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center;">
                        <input type="hidden" name="tab" value="product-management">
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
                </table>
            </div>

            <!-- USER MANAGEMENT -->
            <div id="user-management" class="user-management">
                <div class="tab-controls">
                    <button onclick="location.href='../users/add_user.php'">+ Add New</button>
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center;">
                        <input type="hidden" name="tab" value="user-management">
                        <label>Sort By:</label>
                        <select name="user_sort" onchange="this.form.submit()">
                            <option value="A-Z" <?= $user_sort == 'A-Z' ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A" <?= $user_sort == 'Z-A' ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                        </select>
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
                </table>
            </div>

            <!-- SUPPLIER MANAGEMENT -->
            <div id="supplier-management" class="supplier-management">
                <div class="tab-controls">
                    <button onclick="location.href='../suppliers/add_suppliers.php'">+ Add New</button>
                    <form method="GET" action="management.php" style="display:inline-flex; gap:12px; align-items:center;">
                        <input type="hidden" name="tab" value="supplier-management">
                        <label>Sort By:</label>
                        <select name="supplier_sort" onchange="this.form.submit()">
                            <option value="A-Z" <?= $supplier_sort == 'A-Z' ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A" <?= $supplier_sort == 'Z-A' ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                        </select>
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
                </table>
            </div>

            <!-- ORDER MANAGEMENT -->
                    <div id="order-management" class="order-management">
                        <!-- papalitan pa yung data neto -->
                        <table class="viewrecords">
                            <tr>
                                <th>Payment ID</th>
                                <th>Sale ID</th>
                                <th>Date</th>
                                <th>Amount Paid</th>
                                <th>Currency</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                            <?php if (mysqli_num_rows($result_orderlogs) == 0): ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 30px; color: #888;">No orders yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php while ($row = mysqli_fetch_assoc($result_orderlogs)): ?>
                                <tr>
                                    <td><?= $row['payment_id'] ?></td>
                                    <td><?= $row['sale_id'] ?></td>
                                    <td><?= $row['payment_date'] ?></td>
                                    <td>P<?= number_format($row['amount_paid'], 2) ?></td>
                                    <td><?= $row['currency_code'] ?></td>
                                    <td><?= $row['payment_method'] ?></td>
                                    <td><?= $row['payment_status'] ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </table>
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
                if (targetId) showTab(targetId);
            });
        });
        

        // restore active tab after sort/filter
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = '<?= $active_tab ?>';
            showTab(activeTab);
        });
    </script>
</html>

<?php mysqli_close($conn); ?>