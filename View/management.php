<!---
    MANAGEMENT PAGE

    NOTE :

    TODO :
    [ ] connect to db
    [ ] connect to vm
    [ ] update php logic
--->

<?php
    $con = mysqli_connect("localhost", "root", "", "coffee_db") or die("Unable to Connect". mysqli_error());

    $bean_query = "SELECT * 
            FROM coffee_bean cb
                JOIN province p ON cb.origin_province_id=p.province_id;";
    $result_bean = mysqli_query($con, $bean_query);

    $user_query = "SELECT * FROM users";
    $result_user= mysqli_query($con, $user_query);

    $supplier_query = "SELECT * FROM supplier";
    $result_supplier= mysqli_query($con, $supplier_query);
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Management</title>
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
                <select class>
                    <option>Manila Branch</option>
                    <option>Laguna Branch</option>
                </select>
                <img src="images/logo.png" class="logo" alt="Cool Beans Logo" />
                <a href=""><img src="images/user.png" class="icons" /></a>
            </nav>
        </header>

        <div class="breadcrumb"></div>
        <h2>Management</h2>

        <div class="management-options">
            <button class="nav-btn" data-target="product-management">Manage Products</button>
            <button class="nav-btn" data-target="user-management">Manage Users</button>
            <button class="nav-btn" data-target="supplier-management">Manage Suppliers</button>
            <button class="nav-btn">View Order Log</button>
        </div>

        <div class="table-container">
            <div class="heading">
                <h3>Products</h3>

                <div class="filter_sort">
                    <label>Filter By:</label>
                    <select>
                        <option value="all">All Items</option>
                        <option value="variety">Variety</option>
                        <option value="origin_province">Origin Province</option>
                        <option value="roast_level">Roast Level</option>
                    </select>

                    <label>Sort By:</label>
                    <select> 
                        <option value="A-Z">Alphabetically, A-Z</option>
                        <option value="Z-A">Alphabetically, Z-A</option>
                        <option value="low-high">Price, Low-High</option>
                        <option value="high-low">Price, High-Low</option>
                    </select>
                </div>
            </div>

            <!-- DISPLAY TABLES USING PHP -->
            <!-- PRODUCT MANAGEMENT -->
            <div id="product-management" class="product-management">
                <button onclick="">+ Add New</button>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>

                <?php
                while ($row = mysqli_fetch_assoc($result_bean)) {
                        echo "<tr>";
                        echo "<td>" . $row['bean_id'] . "</td>";
                        echo "<td>" . $row['bean_name'] . "</td>";
                        echo "<td>" . $row['variety'] . "</td>";
                        echo "<td>" . $row['province_name'] . "</td>";
                        echo "<td>" . $row['roast_level'] . "</td>";
                        echo "<td>" . $row['price_per_kg'] . "</td>";
                        echo "<td> 
                                <a href='update.php?id=" . $row['bean_id'] . "'>Edit</a> | 
                                <a href='delete.php?id=" . $row['bean_id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a> 
                              </td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>

            <!-- USER MANAGEMENT -->
            <div id="user-management" class="user-management">
                <button onclick="">+ Add New</button>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Is Active</th>
                    </tr>

                <?php
                while ($row = mysqli_fetch_assoc($result_user)) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['role'] . "</td>";
                        echo "<td>" . $row['is_active'] . "</td>";
                        echo "<td> 
                                <a href='update.php?id=" . $row['bean_id'] . "'>Edit</a> | 
                                <a href='delete.php?id=" . $row['bean_id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a> 
                              </td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>

            <!-- SUPPLIER MANAGEMENT -->
            <div id="supplier-management" class="supplier-management">
                <button onclick="">+ Add New</button>
                <table class="viewrecords">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>City</th>
                    </tr>

                <?php
                while ($row = mysqli_fetch_assoc($result_supplier)) {
                        echo "<tr>";
                        echo "<td>" . $row['supplier_id'] . "</td>";
                        echo "<td>" . $row['supplier_name'] . "</td>";
                        echo "<td>" . $row['contact_number'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['city_id'] . "</td>";
                        echo "<td> 
                                <a href='update.php?id=" . $row['bean_id'] . "'>Edit</a> | 
                                <a href='delete.php?id=" . $row['bean_id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a> 
                              </td>";
                        echo "</tr>";
                    }
                ?>
                </table>
            </div>
        </div>
    </body>


    <!-- for the buttons -->
    <script>
        const navButtons = document.querySelectorAll('.nav-btn');
        const sections = document.querySelectorAll('.table-container > div[id]');
        const mainHeading = document.querySelector('.heading h3');

        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                // change button color
                navButtons.forEach(btn => btn.classList.remove('active-btn'));
                this.classList.add('active-btn');

                // hide other tables
                sections.forEach(section => {
                    section.style.display = 'none';
                });

                // show section
                const targetId = this.getAttribute('data-target');
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    targetSection.style.display = 'block';
                    mainHeading.innerText = this.innerText.replace('Manage ', '');
                }
            });
        });

        // autoclick first tab
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('[data-target="product-management"]').click();
        });
    </script>
</html>

<?php
    mysqli_close($con);
?>