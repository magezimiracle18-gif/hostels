<?php
/* ================================================
   includes/navbar.php - Reusable Navigation Bar
   ================================================ */
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config.php';
}
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="container">
        <!-- Brand -->
        <a class="nav-brand" href="<?= APP_URL ?>/index.php">
            <div class="nav-brand-icon">H</div>
            <div class="nav-brand-text">
                Hostel Mate
                <span>Mbarara University</span>
            </div>
        </a>

        <!-- Main Links -->
        <div class="nav-links">
            <a href="<?= APP_URL ?>/index.php"     class="<?= $current === 'index.php'    ? 'active' : '' ?>">Home</a>
            <a href="<?= APP_URL ?>/hostels.php"   class="<?= $current === 'hostels.php'  ? 'active' : '' ?>">Hostels</a>
            <a href="<?= APP_URL ?>/about.php"     class="<?= $current === 'about.php'    ? 'active' : '' ?>">About</a>
            <a href="<?= APP_URL ?>/faq.php"       class="<?= $current === 'faq.php'      ? 'active' : '' ?>">FAQ</a>
            <a href="<?= APP_URL ?>/contact.php"   class="<?= $current === 'contact.php'  ? 'active' : '' ?>">Contact</a>
        </div>

        <!-- Auth Buttons -->
        <div class="nav-auth">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <a href="<?= APP_URL ?>/admin/index.php" class="btn btn-ghost btn-sm">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/settings.svg" alt="Admin"> Admin
                    </a>
                <?php endif; ?>
                <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-outline btn-sm">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/user.svg" alt="Profile"> <?= sanitize($_SESSION['fullname'] ?? 'Dashboard') ?>
                </a>
                <a href="<?= APP_URL ?>/logout.php" class="btn btn-ghost btn-sm">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/logout.svg" alt="Logout"> Logout
                </a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/login.php"  class="btn btn-ghost btn-sm">Login</a>
                <a href="<?= APP_URL ?>/signup.php" class="btn btn-primary btn-sm">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
