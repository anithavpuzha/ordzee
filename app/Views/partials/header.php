<?php
$sellerName = $sellerName ?? 'Seller';
$sellerInitial = strtoupper(substr(trim($sellerName), 0, 1));
$sellerInitial = $sellerInitial !== '' ? $sellerInitial : 'S';
?>

<!-- Mobile header -->
<div class="mobile-topbar">

    <a href="<?= site_url('dashboard') ?>" class="mobile-brand">
        <span class="brand-mark">O</span>
        Ordzee
    </a>

    <button class="menu-toggle"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mobileNavigation"
            aria-controls="mobileNavigation">
        ☰ Menu
    </button>

</div>

<!-- Page heading -->
<header class="topbar">

    <div>
        <h1 class="page-heading">
            <?= esc($pageTitle ?? 'Dashboard') ?>
        </h1>

        <p class="muted mb-0">
            <?= esc($pageDescription ?? '') ?>
        </p>
    </div>

    <div class="d-flex align-items-center gap-2">

        <div class="text-end">
            <div class="seller-email">
                <?= esc($sellerName) ?>
            </div>

            <div class="muted" style="font-size:11px;">
                Seller account
            </div>
        </div>

        <div class="seller-avatar">
            <?= esc($sellerInitial) ?>
        </div>

    </div>

</header>