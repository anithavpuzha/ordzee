<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<!-- Welcome panel -->
<section class="panel welcome-panel mb-4">

    <div class="d-flex justify-content-between align-items-center gap-3">

        <div>

            <div class="welcome-title">Welcome to Ordzee!</div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('success')) ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($business)): ?>
                <div class="mb-3">

                    <h5 class="fw-bold">
                        <?= esc($business['business_name']) ?>
                    </h5>

                    <span class="text-muted small">
                        <?= esc($business['business_type'] ?? 'Business') ?>
                    </span>

                    <div class="mt-2">
                        <strong class="small">Your Public Menu Link:</strong>
                        <br>

                        <a href="<?= site_url('menu/' . $business['slug']) ?>"
                           target="_blank"
                           rel="noopener noreferrer">
                            <?= esc(site_url('menu/' . $business['slug'])) ?>
                        </a>
                    </div>

                </div>
            <?php endif; ?>

            <p class="muted mb-3">
                Create daily menus, copy previous menus, add or
                remove items, update prices and manage customer
                orders from one place.
            </p>

            <a href="<?= site_url('daily-menu') ?>"
               class="btn btn-ordzee">
                Manage Today's Menu &rarr;
            </a>

        </div>

        <div class="welcome-icon">O</div>

    </div>

</section>


<!-- Summary cards -->
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

        <div class="empty-title">
            Your orders will appear here
        </div>

        <p class="muted mb-0">
            When customers place orders, you'll be able to
            review them from this dashboard.
        </p>

    </div>

</section>

<?= $this->endSection() ?>