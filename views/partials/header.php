<?php
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
?>
<header>
  <nav>
    <div class="nav-links">
      <a href="/itdbadm-mp/coffee-backend/index.php">Home</a>
      <a href="/itdbadm-mp/views/beans.php">Beans</a>
      <a href="/">About Us</a>
    </div>

    <img src="/itdbadm-mp/public/common/logo.png" class="logo" alt="Cool Beans Logo" />

    <div class="nav-icons">
    <div class="search-wrapper">
        <input type="text" class="search-input" placeholder="Search beans...">
        <a href="#" class="search-toggle">
            <img src="/itdbadm-mp/public/common/search.png" class="icons" />
        </a>
    </div>
    <a href="#"><img src="/itdbadm-mp/public/common/shopping-cart.png" class="cart-icon" /></a>
    
    <?php if ($is_logged_in): ?>
        <span><?= htmlspecialchars($_SESSION['username']) ?></span>
        <div class="user-dropdown">
            <a href="#"><img src="/itdbadm-mp/public/common/user.png" class="login-icon" /></a>
            <div class="dropdown-menu">
                <?php if ($is_admin): ?>
                    <a href="/itdbadm-mp/coffee-backend/management/management.php">Management</a>
                <?php endif; ?>
                <a href="/itdbadm-mp/coffee-backend/auth/logout.php">Logout</a>
            </div>
        </div>
    <?php else: ?>
        <a href="#"><img src="/itdbadm-mp/public/common/user.png" class="login-icon" /></a>
    <?php endif; ?>
</div>
  </nav>
</header>

<?php include __DIR__ . '\cart.php'; ?>
<?php if (!$is_logged_in): ?>
  <?php include __DIR__ . '\login.php'; ?>
<?php endif; ?>