
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($pageTitle ?? 'Dashboard') ?> | Ordzee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        :root {
            --ordzee-navy: #172554;
            --ordzee-teal: #0d9488;
            --ordzee-bg: #f5f7fb;
            --ordzee-text: #1e293b;
            --ordzee-muted: #64748b;
            --ordzee-border: #e8edf4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--ordzee-bg);
            color: var(--ordzee-text);
            font-family: Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", sans-serif;
        }

        a {
            text-decoration: none;
        }

        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            padding: 26px 18px;
            background: var(--ordzee-navy);
            color: #fff;
            overflow-y: auto;
            z-index: 1030;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 10px 28px;
            color: #fff;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: var(--ordzee-teal);
            color: white;
            font-size: 23px;
            font-weight: 800;
        }

        .brand-subtitle {
            margin-top: 2px;
            color: #b8c7e5;
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.7px;
        }

        .nav-label {
            margin: 16px 12px 10px;
            color: #91a3c6;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sidebar .nav-link,
        .mobile-menu .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 5px;
            padding: 12px 13px;
            border-radius: 10px;
            color: #d5def0;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar .nav-link:hover,
        .mobile-menu .nav-link:hover {
            background: rgba(255, 255, 255, 0.09);
            color: #fff;
        }

        .sidebar .nav-link.active,
        .mobile-menu .nav-link.active {
            background: var(--ordzee-teal);
            color: #fff;
        }

        .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            font-size: 17px;
        }

        .sidebar-footer {
            margin-top: 30px;
            padding: 16px 12px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .sidebar-footer .small {
            color: #b8c7e5;
        }

        .main-content {
            min-height: 100vh;
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .page-heading {
            margin: 0;
            color: var(--ordzee-navy);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .muted {
            color: var(--ordzee-muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .seller-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 17px;
            font-weight: 800;
        }

        .seller-email {
            max-width: 220px;
            overflow: hidden;
            color: var(--ordzee-navy);
            font-size: 12px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .panel {
            height: 100%;
            padding: 22px;
            border: 1px solid var(--ordzee-border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(23, 37, 84, 0.025);
        }

        .welcome-panel {
            position: relative;
            overflow: hidden;
            padding: 30px;
            background: linear-gradient(120deg, #fff 45%, #e6fffb 100%);
        }

        .welcome-title {
            margin-bottom: 10px;
            color: var(--ordzee-navy);
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.7px;
        }

        .welcome-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 78px;
            height: 78px;
            flex-shrink: 0;
            border-radius: 24px;
            background: var(--ordzee-navy);
            color: #fff;
            font-size: 43px;
            font-weight: 800;
        }

        .btn-ordzee {
            padding: 11px 17px;
            border: 0;
            border-radius: 9px;
            background: var(--ordzee-teal);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
        }

        .btn-ordzee:hover {
            background: #0f766e;
            color: #fff;
        }

        .metric-card {
            padding: 21px;
        }

        .metric-label {
            margin-bottom: 13px;
            color: var(--ordzee-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .metric-value {
            margin-bottom: 8px;
            color: var(--ordzee-navy);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .metric-note {
            color: var(--ordzee-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .section-title {
            margin: 0;
            color: var(--ordzee-navy);
            font-size: 16px;
            font-weight: 800;
        }

        .section-description {
            margin-top: 5px;
            color: var(--ordzee-muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .quick-action {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 16px 0;
            border-bottom: 1px solid var(--ordzee-border);
            color: inherit;
        }

        .quick-action:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .action-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 12px;
            background: #e6fffb;
            color: #0f766e;
            font-size: 19px;
        }

        .action-title {
            margin-bottom: 4px;
            color: var(--ordzee-navy);
            font-size: 13px;
            font-weight: 700;
        }

        .action-description {
            color: var(--ordzee-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .unit-chip {
            display: inline-block;
            margin: 4px 4px 4px 0;
            padding: 6px 10px;
            border: 1px solid #dbe7f0;
            border-radius: 20px;
            background: #f8fafc;
            color: #334155;
            font-size: 11px;
            font-weight: 600;
        }

        .empty-state {
            padding: 28px 10px;
            text-align: center;
        }

        .empty-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            margin: 0 auto 14px;
            border-radius: 16px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 25px;
        }

        .empty-title {
            margin-bottom: 6px;
            color: var(--ordzee-navy);
            font-size: 14px;
            font-weight: 700;
        }

        .mobile-topbar {
            display: none;
        }

        .mobile-menu {
            background: var(--ordzee-navy);
            color: white;
        }

        .mobile-menu .nav-link {
            color: #d5def0;
        }

        .mobile-menu .nav-link.active {
            color: #fff;
        }

        .logout-button {
            width: 100%;
            padding: 10px 13px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: #d5def0;
            font-size: 13px;
            text-align: left;
        }

        .logout-button:hover {
            background: rgba(255, 255, 255, 0.09);
            color: white;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-bottom: 24px;
            }

            .mobile-brand {
                display: flex;
                align-items: center;
                gap: 9px;
                color: var(--ordzee-navy);
                font-size: 21px;
                font-weight: 800;
            }

            .mobile-brand .brand-mark {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                font-size: 20px;
            }

            .menu-toggle {
                padding: 8px 12px;
                border: 1px solid var(--ordzee-border);
                border-radius: 9px;
                background: #fff;
                color: var(--ordzee-navy);
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 15px;
            }

            .topbar {
                align-items: flex-start;
            }

            .page-heading {
                font-size: 22px;
            }

            .welcome-panel {
                padding: 22px;
            }

            .welcome-title {
                font-size: 21px;
            }

            .welcome-icon {
                width: 50px;
                height: 50px;
                border-radius: 15px;
                font-size: 28px;
            }

            .panel {
                padding: 18px;
            }

            .metric-card {
                padding: 17px;
            }

            .metric-value {
                font-size: 26px;
            }

            .seller-email {
                max-width: 125px;
            }
        }
    </style>
</head>

<body>

<?php
    $sellerName = $sellerName ?? 'Seller';
    $sellerInitial = strtoupper(substr(trim($sellerName), 0, 1));
    $sellerInitial = $sellerInitial !== '' ? $sellerInitial : 'S';
?>

<!-- Desktop sidebar -->
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
        <a class="nav-link active" href="<?= site_url('dashboard') ?>">
            <span class="nav-icon">◈</span> Dashboard
        </a>

        <a class="nav-link" href="<?= site_url('daily-menu') ?>">
            <span class="nav-icon">▦</span> Daily Menu
        </a>

        <a class="nav-link" href="<?= site_url('products') ?>">
            <span class="nav-icon">▤</span> Products
        </a>

        <a class="nav-link" href="<?= site_url('orders') ?>">
            <span class="nav-icon">▣</span> Customer Orders
        </a>

        <a class="nav-link" href="<?= site_url('preparation') ?>">
            <span class="nav-icon">◷</span> Preparation
        </a>

        <a class="nav-link" href="<?= site_url('delivery') ?>">
            <span class="nav-icon">↗</span> Delivery
        </a>

        <a class="nav-link" href="<?= site_url('payments') ?>">
            <span class="nav-icon">₹</span> Payments
        </a>
    </nav>

    <div class="nav-label">Manage</div>

    <nav class="nav flex-column">
        <a class="nav-link" href="<?= site_url('settings') ?>">
            <span class="nav-icon">⚙</span> Business Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="small mb-2">Signed in as</div>

        <div class="text-white small mb-3"
             style="overflow-wrap:anywhere;">
            <?= esc($sellerName) ?>
        </div>

        <a href="<?= url_to('logout') ?>" class="logout-button">
            ↪ &nbsp; Sign Out
        </a>
    </div>
</aside>

<!-- Mobile navigation -->
<div
    class="offcanvas offcanvas-start mobile-menu"
    tabindex="-1"
    id="mobileNavigation"
    aria-labelledby="mobileNavigationLabel"
>
    <div class="offcanvas-header">
        <div class="brand mb-0 pb-0" id="mobileNavigationLabel">
            <span class="brand-mark">O</span>
            <span>Ordzee</span>
        </div>

        <button
            type="button"
            class="btn-close btn-close-white"
            data-bs-dismiss="offcanvas"
            aria-label="Close"
        ></button>
    </div>

    <div class="offcanvas-body">
        <div class="nav-label">Workspace</div>

        <nav class="nav flex-column">
            <a class="nav-link active" href="<?= site_url('dashboard') ?>">
                <span class="nav-icon">◈</span> Dashboard
            </a>

            <a class="nav-link" href="<?= site_url('daily-menu') ?>">
                <span class="nav-icon">▦</span> Daily Menu
            </a>

            <a class="nav-link" href="<?= site_url('products') ?>">
                <span class="nav-icon">▤</span> Products
            </a>

            <a class="nav-link" href="<?= site_url('orders') ?>">
                <span class="nav-icon">▣</span> Customer Orders
            </a>

            <a class="nav-link" href="<?= site_url('preparation') ?>">
                <span class="nav-icon">◷</span> Preparation
            </a>

            <a class="nav-link" href="<?= site_url('delivery') ?>">
                <span class="nav-icon">↗</span> Delivery
            </a>

            <a class="nav-link" href="<?= site_url('payments') ?>">
                <span class="nav-icon">₹</span> Payments
            </a>
        </nav>

        <div class="nav-label">Manage</div>

        <nav class="nav flex-column">
            <a class="nav-link" href="<?= site_url('settings') ?>">
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

<!-- Main dashboard -->
<main class="main-content">

    <!-- Mobile header -->
    <div class="mobile-topbar">
        <a href="<?= site_url('dashboard') ?>" class="mobile-brand">
            <span class="brand-mark">O</span>
            Ordzee
        </a>

        <button
            class="menu-toggle"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#mobileNavigation"
            aria-controls="mobileNavigation"
            aria-label="Open navigation menu"
        >
            ☰ Menu
        </button>
    </div>

    <!-- Page heading -->
    <header class="topbar">
        <div>
            <h1 class="page-heading">Dashboard</h1>
            <p class="muted mb-0">
                Your business overview and daily operations.
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

    <!-- Welcome panel -->
    <section class="panel welcome-panel mb-4">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div>
                <div class="welcome-title">Welcome to Ordzee!</div>

                <p class="muted mb-3">
                    Manage today's menu, different selling options,
                    prices, stock quantities and customer orders
                    from one place.
                </p>

                <a href="<?= site_url('daily-menu') ?>"
                   class="btn btn-ordzee">
                    Manage Today's Menu &rarr;
                </a>
            </div>

            <div class="welcome-icon">O</div>
        </div>
    </section>

    <!-- Summary cards: placeholders until database integration -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="panel metric-card">
                <div class="metric-label">Today's Menu Items</div>
                <div class="metric-value">0</div>
                <div class="metric-note">
                    Products and selling options listed today
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="panel metric-card">
                <div class="metric-label">Available Stock</div>
                <div class="metric-value">0</div>
                <div class="metric-note">
                    Stock tracked using each item's own unit
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="panel metric-card">
                <div class="metric-label">Today's Orders</div>
                <div class="metric-value">0</div>
                <div class="metric-note">
                    Customer orders received today
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="panel metric-card">
                <div class="metric-label">Pending Payments</div>
                <div class="metric-value">₹0</div>
                <div class="metric-note">
                    Outstanding customer balances
                </div>
            </div>
        </div>

    </div>

    <!-- Quick actions and predefined units -->
    <div class="row g-4 mb-4">

        <div class="col-12 col-lg-7">
            <section class="panel">
                <h2 class="section-title">Quick Actions</h2>
                <p class="section-description mb-1">
                    Shortcuts to your daily seller tasks.
                </p>

                <a href="<?= site_url('daily-menu') ?>" class="quick-action">
                    <span class="action-icon">▦</span>
                    <span>
                        <span class="action-title d-block">
                            Manage Daily Menu
                        </span>
                        <span class="action-description d-block">
                            Choose products, set today's prices,
                            select selling options and update availability.
                        </span>
                    </span>
                </a>

                <a href="<?= site_url('products') ?>" class="quick-action">
                    <span class="action-icon">＋</span>
                    <span>
                        <span class="action-title d-block">
                            Manage Products
                        </span>
                        <span class="action-description d-block">
                            Create reusable products such as rice, juice
                            and meals, with one or more selling options.
                        </span>
                    </span>
                </a>

                <a href="<?= site_url('orders') ?>" class="quick-action">
                    <span class="action-icon">▣</span>
                    <span>
                        <span class="action-title d-block">
                            View Customer Orders
                        </span>
                        <span class="action-description d-block">
                            Review orders and the exact option, quantity
                            and price selected by each customer.
                        </span>
                    </span>
                </a>

                <a href="<?= site_url('preparation') ?>" class="quick-action">
                    <span class="action-icon">◷</span>
                    <span>
                        <span class="action-title d-block">
                            Prepare Orders
                        </span>
                        <span class="action-description d-block">
                            Organise accepted orders for preparation
                            and packing.
                        </span>
                    </span>
                </a>
            </section>
        </div>

        <div class="col-12 col-lg-5">
            <section class="panel">
                <h2 class="section-title">Predefined Units</h2>
                <p class="section-description mb-3">
                    Ordzee will provide a ready-made unit list.
                    Sellers will choose from the available units.
                </p>

                <div class="mb-3">
                    <div class="metric-label mb-1">Weight</div>
                    <span class="unit-chip">Gram (g)</span>
                    <span class="unit-chip">Kilogram (kg)</span>
                </div>

                <div class="mb-3">
                    <div class="metric-label mb-1">Volume</div>
                    <span class="unit-chip">Millilitre (ml)</span>
                    <span class="unit-chip">Litre (L)</span>
                </div>

                <div class="mb-3">
                    <div class="metric-label mb-1">Individual items</div>
                    <span class="unit-chip">Piece</span>
                    <span class="unit-chip">Bottle</span>
                    <span class="unit-chip">Packet</span>
                    <span class="unit-chip">Box</span>
                    <span class="unit-chip">Cup</span>
                    <span class="unit-chip">Dozen</span>
                </div>

                <div>
                    <div class="metric-label mb-1">Food portions</div>
                    <span class="unit-chip">Half plate</span>
                    <span class="unit-chip">Full plate</span>
                </div>

                <p class="muted mt-3 mb-0" style="font-size:11px;">
                    These are the planned choices for the product and
                    menu screens. The actual unit selection and stock
                    calculations will be implemented in those modules.
                </p>
            </section>
        </div>

    </div>

    <!-- Recent orders -->
    <section class="panel">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <div>
                <h2 class="section-title">Recent Orders</h2>
                <p class="section-description mb-0">
                    Keep track of your latest customer orders.
                </p>
            </div>

            <a href="<?= site_url('orders') ?>"
               class="btn btn-outline-secondary btn-sm">
                View All
            </a>
        </div>

        <div class="empty-state">
            <div class="empty-icon">▣</div>

            <div class="empty-title">Your orders will appear here</div>

            <p class="muted mb-0">
                When customers place orders, you'll be able to
                review them from this dashboard.
            </p>
        </div>
    </section>

    <footer class="text-center mt-4 mb-2">
        <span class="muted" style="font-size:11px;">
            Ordzee Seller Workspace
        </span>
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>