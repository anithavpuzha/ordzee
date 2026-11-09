<?php
$activePage = $activePage ?? 'dashboard';
?>

<aside class="sidebar">

    <a href="<?= site_url('dashboard') ?>" class="brand">
        <span class="brand-mark">O</span>
        <span>
            Ordzee
            <span class="brand-subtitle d-block">SELLER WORKSPACE</span>
        </span>
    </a>

    <div class="nav-label">Workspace</div>

    <nav class="nav flex-column">

        <a class="nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>"
           href="<?= site_url('dashboard') ?>">
            <span class="nav-icon">◈</span> Dashboard
        </a>

        <a class="nav-link <?= $activePage === 'daily-menu' ? 'active' : '' ?>"
           href="<?= site_url('daily-menu') ?>">
            <span class="nav-icon">▦</span> Daily Menu
        </a>

        <a class="nav-link <?= $activePage === 'products' ? 'active' : '' ?>"
           href="<?= site_url('products') ?>">
            <span class="nav-icon">▤</span> Products
        </a>

        <a class="nav-link <?= $activePage === 'orders' ? 'active' : '' ?>"
           href="<?= site_url('orders') ?>">
            <span class="nav-icon">▣</span> Customer Orders
        </a>

    <!--    <a class="nav-link <?= $activePage === 'preparation' ? 'active' : '' ?>"
           href="<?= site_url('preparation') ?>">
            <span class="nav-icon">◷</span> Preparation
        </a>

        <a class="nav-link <?= $activePage === 'delivery' ? 'active' : '' ?>"
           href="<?= site_url('delivery') ?>">
            <span class="nav-icon">↗</span> Delivery
        </a> -->

        <a class="nav-link <?= $activePage === 'payments' ? 'active' : '' ?>"
           href="<?= site_url('payments') ?>">
            <span class="nav-icon">₹</span> Payments
        </a>

    </nav>

    <div class="nav-label">Manage</div>

    <nav class="nav flex-column">
        <a class="nav-link <?= $activePage === 'settings' ? 'active' : '' ?>"
           href="<?= site_url('settings') ?>">
            <span class="nav-icon">⚙</span> Business Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="small mb-2">Signed in as</div>

        <div class="text-white small mb-3" style="overflow-wrap:anywhere;">
            <?= esc($sellerName ?? 'Seller') ?>
        </div>

        <a href="<?= url_to('logout') ?>" class="logout-button">
            ↪ &nbsp; Sign Out
        </a>
    </div>

</aside>