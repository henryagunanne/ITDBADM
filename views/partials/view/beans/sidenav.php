<sidenav>
    <div class="filter">
        <h4>Filter</h4>
        <form action="/itdbadm-mp/views/beans.php" method="GET" class="options">
            <!-- preserve sort and search -->
            <input type="hidden" name="sort"   value="<?= htmlspecialchars($sort) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

            <h5>Origin</h5>
            <select name="origin" onchange="this.form.submit()">
                <option value="">All Origins</option>
                <?php foreach ($provinces as $province): ?>
                    <option value="<?= $province['province_id'] ?>"
                        <?= $origin == $province['province_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($province['province_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h5>Variety</h5>
            <select name="variety" onchange="this.form.submit()">
                <option value="">All Varieties</option>
                <?php foreach ($varieties as $v): ?>
                    <option value="<?= htmlspecialchars($v) ?>"
                        <?= $variety == $v ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h5>Roast Level</h5>
            <select name="roast_level" onchange="this.form.submit()">
                <option value="">All Roast Levels</option>
                <option value="LIGHT"       <?= $roast == 'LIGHT'       ? 'selected' : '' ?>>Light</option>
                <option value="MEDIUM"      <?= $roast == 'MEDIUM'      ? 'selected' : '' ?>>Medium</option>
                <option value="MEDIUM_DARK" <?= $roast == 'MEDIUM_DARK' ? 'selected' : '' ?>>Medium Dark</option>
                <option value="DARK"        <?= $roast == 'DARK'        ? 'selected' : '' ?>>Dark</option>
            </select>

            <h5>Price Range</h5>
            <div class="price-range">
                <input type="number" name="min_price" placeholder="Min Price"
                       value="<?= htmlspecialchars($min_price) ?>">
                <input type="number" name="max_price" placeholder="Max Price"
                       value="<?= htmlspecialchars($max_price) ?>">
                <input type="submit" value="Apply" class="apply-btn">
            </div>
        </form>
    </div>

    <div class="sort">
        <h4>Sort By</h4>
        <form action="/itdbadm-mp/views/beans.php" method="GET">
            <!-- preserve filters when sorting -->
            <input type="hidden" name="search"     value="<?= htmlspecialchars($search) ?>">
            <input type="hidden" name="origin"     value="<?= htmlspecialchars($origin) ?>">
            <input type="hidden" name="variety"    value="<?= htmlspecialchars($variety) ?>">
            <input type="hidden" name="roast_level" value="<?= htmlspecialchars($roast) ?>">
            <input type="hidden" name="min_price"  value="<?= htmlspecialchars($min_price) ?>">
            <input type="hidden" name="max_price"  value="<?= htmlspecialchars($max_price) ?>">
            <select name="sort" onchange="this.form.submit()">
                <option value="A-Z"      <?= $sort == 'A-Z'      ? 'selected' : '' ?>>Alphabetically, A-Z</option>
                <option value="Z-A"      <?= $sort == 'Z-A'      ? 'selected' : '' ?>>Alphabetically, Z-A</option>
                <option value="low-high" <?= $sort == 'low-high' ? 'selected' : '' ?>>Price, Low-High</option>
                <option value="high-low" <?= $sort == 'high-low' ? 'selected' : '' ?>>Price, High-Low</option>
            </select>
        </form>
    </div>
</sidenav>

<div class="vertical-divider"></div>