<sidenav>
    <div class="filter">
        <h4>Filter</h4>
        <form action="" method="POST" class="options">
            <h5>Origin</h5>
            <select name="origin">
                <?php foreach ($provinces as $province): ?>
                    <option value="<?= $province['province_id'] ?>">
                        <?= htmlspecialchars($province['province_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h5>Variety</h5>
            <select name="variety">
                <?php foreach ($varieties as $variety): ?>
                    <option value="<?= htmlspecialchars($variety) ?>">
                        <?= htmlspecialchars($variety) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h5>Roast Level</h5>
            <select name="roast_level">
                <option value="LIGHT">Light</option>
                <option value="MEDIUM">Medium</option>
                <option value="MEDIUM_DARK">Medium Dark</option>
                <option value="DARK">Dark</option>
            </select>

            <h5>Price Range</h5>
            <div class="price-range">
                <input type="number" name="min_price" placeholder="Min Price">
                <input type="number" name="max_price" placeholder="Max Price">
                <input type="submit" value="Apply" class="apply-btn">
            </div>
        </form>
    </div>

    <div class="sort">
        <h4>Sort By</h4>
        <form action="" method="POST">
            <select name="name">
                <option value="A-Z">Alphabetically, A-Z</option>
                <option value="Z-A">Alphabetically, Z-A</option>
                <option value="low-high">Price, Low-High</option>
                <option value="high-low">Price, High-Low</option>
            </select>
        </form>
    </div>
</sidenav>

<div class="vertical-divider"></div>