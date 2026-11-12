<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<!-- Welcome panel -->
<section class="panel welcome-panel mb-4">

    <div class="d-flex justify-content-between align-items-center gap-3">

        <div>

            <div class="welcome-title">Welcome to Ordzee!</div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3"
                     role="alert">

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
                Manage your permanent menu, receive customer orders
                and keep track of payments from one place.
            </p>

            <a href="<?= site_url('daily-menu') ?>"
               class="btn btn-ordzee">

                Manage Menu &rarr;

            </a>

        </div>

        <div class="welcome-icon">O</div>

    </div>

</section>


<!-- Business summary cards -->
<div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">

        <div class="panel metric-card">

            <div class="metric-label">Total Orders</div>

            <div class="metric-value">
                <?= number_format($totalOrders) ?>
            </div>

            <div class="metric-note">
                All customer orders received
            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="panel metric-card">

            <div class="metric-label">Today's Orders</div>

            <div class="metric-value">
                <?= number_format($todayOrders) ?>
            </div>

            <div class="metric-note">
                Orders received today
            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="panel metric-card">

            <div class="metric-label">Pending Orders</div>

            <div class="metric-value">
                <?= number_format($pendingOrders) ?>
            </div>

            <div class="metric-note">
                Orders waiting for your action
            </div>

        </div>

    </div>


    <div class="col-12 col-sm-6 col-xl-3">

        <div class="panel metric-card">

            <div class="metric-label">Outstanding Payments</div>

            <div class="metric-value">
                ₹<?= number_format($pendingPayments, 2) ?>
            </div>

            <div class="metric-note">
                Remaining customer payment balance
            </div>

        </div>

    </div>

</div>


<!-- Sales Overview -->
<section class="panel mb-4">

    <h2 class="section-title">Sales Overview</h2>

    <p class="section-description">
        Value of orders marked as delivered during each period.
    </p>

    <div class="row g-3">

        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-label">Today's Sales</div>

                <div class="metric-value">
                    ₹<?= number_format($todaySales, 2) ?>
                </div>

                <div class="metric-note">
                    Delivered today
                </div>

            </div>

        </div>


        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-label">This Week's Sales</div>

                <div class="metric-value">
                    ₹<?= number_format($weeklySales, 2) ?>
                </div>

                <div class="metric-note">
                    Monday to today
                </div>

            </div>

        </div>


        <div class="col-12 col-md-4">

            <div class="metric-card">

                <div class="metric-label">This Month's Sales</div>

                <div class="metric-value">
                    ₹<?= number_format($monthlySales, 2) ?>
                </div>

                <div class="metric-note">
                    First day of month to today
                </div>

            </div>

        </div>

    </div>

    <p class="muted mt-3 mb-0" style="font-size: 11px;">
        Sales are calculated from delivered order totals.
        They represent order value, not actual payments collected.
    </p>

</section>


<!-- Quick Actions -->
<section class="panel mb-4">

    <h2 class="section-title">Quick Actions</h2>

    <p class="section-description mb-1">
        Shortcuts to your important business tasks.
    </p>


    <a href="<?= site_url('daily-menu') ?>" class="quick-action">

        <span class="action-icon">▦</span>

        <span>

            <span class="action-title d-block">
                Manage Menu
            </span>

            <span class="action-description d-block">
                Add products to your menu, update units and prices,
                and manage item availability.
            </span>

        </span>

    </a>


    <a href="<?= site_url('products') ?>" class="quick-action">

        <span class="action-icon">＋</span>

        <span>

            <span class="action-title d-block">
                Product Catalogue
            </span>

            <span class="action-description d-block">
                Add and manage reusable products for your menu.
            </span>

        </span>

    </a>


    <a href="<?= site_url('orders') ?>" class="quick-action">

        <span class="action-icon">▣</span>

        <span>

            <span class="action-title d-block">
                Manage Orders
            </span>

            <span class="action-description d-block">
                View customer orders, update order status
                and manage payments.
            </span>

        </span>

    </a>

</section>


<!-- Recent Orders -->
<section class="panel">

    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">

        <div>

            <h2 class="section-title">Recent Orders</h2>

            <p class="section-description mb-0">
                Your latest customer orders.
            </p>

        </div>

        <a href="<?= site_url('orders') ?>"
           class="btn btn-outline-secondary btn-sm">

            View All

        </a>

    </div>


    <?php if (! empty($recentOrders)): ?>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Order Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($recentOrders as $order): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= esc($order['order_number']) ?>
                            </td>

                            <td>
                                <?= esc($order['customer_name']) ?>
                            </td>

                            <td>
                                ₹<?= number_format((float) $order['grand_total'], 2) ?>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    <?= esc(ucfirst($order['order_status'])) ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    <?= esc(ucfirst($order['payment_status'])) ?>
                                </span>
                            </td>

                            <td class="small text-muted">
                                <?= esc(date('d M Y', strtotime($order['order_date']))) ?>
                            </td>

                            <td>

                                <a href="<?= site_url('orders/view/' . $order['id']) ?>"
                                   class="btn btn-outline-secondary btn-sm">

                                    View

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty-state">

            <div class="empty-icon">▣</div>

            <div class="empty-title">
                No orders yet
            </div>

            <p class="muted mb-0">
                When customers place orders through your public menu,
                their latest orders will appear here.
            </p>

        </div>

    <?php endif; ?>

</section>


<?= $this->endSection() ?>