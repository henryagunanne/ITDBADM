<!---
    BEANS PAGE

    NOTE :
    > all items lead to item.html

    TODO :
    [x] connect to db
    [ ] connect to succeeding pages
--->

<?php
    require_once '../coffee-backend/config/db-connect.php';

    // base query
    $query = "SELECT cb.*, p.province_name 
              FROM coffee_bean cb
              JOIN province p ON cb.origin_province_id = p.province_id
              WHERE 1=1";

    // filter by origin province
    if (!empty($_POST['origin'])) {
        $origins = array_map(fn($o) => "'" . mysqli_real_escape_string($conn, $o) . "'", $_POST['origin']);
        $query .= " AND p.province_name IN (" . implode(',', $origins) . ")";
    }

    // sort
    $sort = $_POST['name'] ?? 'A-Z';
    switch ($sort) {
        case 'Z-A':       $query .= " ORDER BY cb.bean_name DESC"; break;
        case 'low-high':  $query .= " ORDER BY cb.price_per_kg ASC"; break;
        case 'high-low':  $query .= " ORDER BY cb.price_per_kg DESC"; break;
        default:          $query .= " ORDER BY cb.bean_name ASC"; break;
    }

    $result = mysqli_query($conn, $query);

    // fetch all provinces for filter sidebar
    $province_result = mysqli_query($conn, "SELECT province_name FROM province ORDER BY province_name ASC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Beans</title>
        <link rel="stylesheet" href="style.css">

        <!-- fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Notable&family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
    </head>

    <body>
        <!-- NAVIGATION -->
        <header>
            <nav>
                <div class="nav-links">
                    <a href="home.php">Home</a>
                    <a href="" style="font-weight: bold;">Beans</a>
                    <a href="">About Us</a>
                </div>

                <img src="images/logo.png" class="logo" alt="Cool Beans Logo" />

                <div class="nav-icons">
                    <a href=""><img src="images/search.png" class="icons" /></a>
                    <a href=""><img src="images/shopping-cart.png" class="cart-icon" /></a>
                    <a href=""><img src="images/user.png" class="icons" /></a>
                </div>
            </nav>
        </header>

        <!-- CART POPUP -->
        <div class="cart-container"> 
            <div class="cart-content">
                <h3>Your Cart</h3>
                <hr> 
                <div class="item">
                    <img src="images/coffee-bag.png" class="cart-item-img" />
                    <div class="cart-info">
                        <h5>Arabica</h5>
                        <p class="price">P250.00</p>
                        <div class="qty-row">
                            <span>Quantity:</span>
                            <div class="qty-selector">
                                <button>-</button> <span>1</span> <button>+</button>
                            </div>
                        </div>
                        <p class="item-total">Total: P250.00</p>
                    </div>
                    <button class="delete-btn"><img src="images/bin.png"></button>
                </div>
                
                <div class="cart-footer">
                    <hr>
                    <div class="subtotal-row">
                        <span class="label">Subtotal:</span>
                        <span class="amount">P250.00</span>
                    </div>
                    <button class="checkout-btn" onclick="location.href='checkout.php'">Checkout</button>
                </div>
            </div>
        </div>

        <!-- BREADCRUMB -->
        <div class="breadcrumb">
            <p>Home > Beans</p>
        </div>

        <!-- TITLE -->
        <h2>Coffee Beans</h2>

        <!-- MAIN CONTENT -->
        <div class="main-container">

            <!-- FILTER & SORT BY -->
            <sidenav>
                <div class="filter">
                    <h4>Filter</h4>
                    <form action="beans.php" method="POST" class="options">
                        <h5>Origin</h5>
                        <?php while ($province = mysqli_fetch_assoc($province_result)): ?>
                            <input type="checkbox" name="origin[]" value="<?= $province['province_name'] ?>"
                                <?= (!empty($_POST['origin']) && in_array($province['province_name'], $_POST['origin'])) ? 'checked' : '' ?>>
                            <?= $province['province_name'] ?><br>
                        <?php endwhile; ?>

                        <input type="submit" value="Apply" class="apply-btn">  
                    </form>
                </div>
                
                <div class="sort">
                    <h4>Sort By</h4>
                    <form action="beans.php" method="POST">
                        <select name="name" onchange="this.form.submit()"> 
                            <option value="A-Z"      <?= ($sort == 'A-Z')      ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                            <option value="Z-A"      <?= ($sort == 'Z-A')      ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                            <option value="low-high" <?= ($sort == 'low-high') ? 'selected' : '' ?>>Price, Low-High</option>
                            <option value="high-low" <?= ($sort == 'high-low') ? 'selected' : '' ?>>Price, High-Low</option>
                        </select>
                    </form>
                </div>
            </sidenav>

            <div class="vertical-divider"></div>

            <!-- COFFEE BEANS SELECTION -->
            <section class="beans-container">
                <div class="selection">
                    <div class="product-row">
                        <div class="product-items">

                            <?php if (mysqli_num_rows($result) == 0): ?>
                                <p>No beans found.</p>
                            <?php endif; ?>

                            <?php while ($bean = mysqli_fetch_assoc($result)): ?>
                                <div class="bean-item">
                                    <a href="item.php?id=<?= $bean['bean_id'] ?>">
                                        <h4><?= $bean['bean_name'] ?></h4>
                                        <p>P<?= number_format($bean['price_per_kg'], 2) ?></p>
                                        <img src="images/coffee-bag.png">
                                    </a>
                                    
                                    <div class="controls">
                                        <div class="qty-selector">
                                            <button>-</button> <span>1</span> <button>+</button>
                                        </div>
                                        <button class="add-btn">Add to Cart</button>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- FOOTER -->
        <footer>
            <img src="images/logo.png" />
            
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
    </body>

    <!-- cart popup script -->
    <script>
        const showPopupCart = document.querySelector('.cart-icon');
        const popupContainerCart = document.querySelector('.cart-container');

        showPopupCart.onclick = (e) => {
            e.preventDefault();
            popupContainerCart.classList.toggle('active'); 
        };

        window.onclick = (e) => {
            if (!popupContainerCart.contains(e.target) && !showPopupCart.contains(e.target)) {
                popupContainerCart.classList.remove('active');
            }
        };
    </script>
</html>

<?php mysqli_close($conn); ?>