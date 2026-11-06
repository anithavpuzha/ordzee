<?php
$activePage = $activePage ?? 'dashboard';
?>

<div class="offcanvas offcanvas-start mobile-menu"
     tabindex="-1"
     id="mobileNavigation"
     aria-labelledby="mobileNavigationLabel">

    <div class="offcanvas-header">
        <div class="brand mb-0 pb-0" id="mobileNavigationLabel">
            <span class="brand-mark">O</span>
            <span>Ordzee</span>
        </div>

        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">

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

            <a class="nav-link <?= $activePage === 'preparation' ? 'active' : '' ?>"
               href="<?= site_url('preparation') ?>">
                <span class="nav-icon">◷</span> Preparation
            </a>

            <a class="nav-link <?= $activePage === 'delivery' ? 'active' : '' ?>"
               href="<?= site_url('delivery') ?>">
                <span class="nav-icon">↗</span> Delivery
            </a>

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

        <hr style="border-color:rgba(255,255,255,.2);">

        <form action="<?= url_to('logout') ?>" method="post">
            <?= csrf_field() ?>

            <button type="submit" class="logout-button">
                ↪ &nbsp; Sign Out
            </button>
        </form>

    </div>
</div>