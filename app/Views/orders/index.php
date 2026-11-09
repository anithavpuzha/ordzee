<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<style>
    /* Orders page - scoped styles */
    .orders-page {
        font-family: inherit;
    }

    .orders-page .orders-header {
        margin-bottom: 22px;
    }

    .orders-page .section-description {
        margin-bottom: 0;
    }

    /* Summary cards */
    .orders-page .summary-card {
        height: 100%;
        border: 1px solid #e9edf2;
        border-radius: 12px;
        background: #fff;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
        overflow: hidden;
    }

    .orders-page .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 16px rgba(0, 0, 0, 0.06);
    }

    .orders-page .summary-card .card-body {
        padding: 18px;
    }

    .orders-page .summary-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .orders-page .summary-number {
        font-size: 27px;
        font-weight: 700;
        line-height: 1.2;
        color: #252b33;
    }

    .orders-page .summary-total {
        border-top: 3px solid #6c757d;
    }

    .orders-page .summary-pending {
        border-top: 3px solid #e0a800;
    }

    .orders-page .summary-accepted {
        border-top: 3px solid #0d6efd;
    }

    .orders-page .summary-delivered {
        border-top: 3px solid #198754;
    }

    .orders-page .summary-rejected {
        border-top: 3px solid #dc3545;
    }

    /* Filter section */
    .orders-page .filter-panel {
        background: #f8f9fb;
        border: 1px solid #e9edf2;
        border-radius: 12px;
        padding: 20px;
    }

    .orders-page .filter-heading {
        font-size: 15px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 16px;
    }

    .orders-page .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 7px;
    }

    .orders-page .form-control,
    .orders-page .form-select {
        min-height: 42px;
        border: 1px solid #dfe3e8;
        border-radius: 8px;
        font-size: 14px;
        background-color: #fff;
    }

    .orders-page .form-control:focus,
    .orders-page .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.12);
    }

    .orders-page .filter-actions {
        margin-top: 18px;
    }

    .orders-page .filter-actions .btn {
        min-height: 40px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        padding-left: 17px;
        padding-right: 17px;
    }

    /* Order status filter buttons */
    .orders-page .status-filter-heading {
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 12px;
    }

    .orders-page .status-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 22px;
    }

    .orders-page .status-filter .btn {
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        padding: 7px 16px;
    }

    /* Orders table */
    .orders-page .orders-table-wrapper {
        border: 1px solid #e9edf2;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .orders-page .orders-table {
        margin-bottom: 0;
        font-size: 13px;
    }

    .orders-page .orders-table thead th {
        background: #f6f8fa;
        color: #495057;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        padding: 14px 13px;
        border-bottom: 1px solid #e9edf2;
    }

    .orders-page .orders-table tbody td {
        padding: 13px;
        vertical-align: middle;
        color: #343a40;
        border-color: #edf0f3;
    }

    .orders-page .orders-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .orders-page .orders-table tbody tr:hover {
        background: #fafbfc;
    }

    .orders-page .order-number {
        font-weight: 600;
        color: #343a40;
        white-space: nowrap;
    }

    .orders-page .customer-name {
        font-weight: 600;
        color: #343a40;
    }

    .orders-page .order-total {
        font-weight: 600;
        white-space: nowrap;
    }

    .orders-page .status-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .orders-page .payment-pending {
        background: #fff3cd;
        color: #856404;
    }

    .orders-page .payment-partial {
        background: #cff4fc;
        color: #055160;
    }

    .orders-page .payment-paid {
        background: #d1e7dd;
        color: #0f5132;
    }

    .orders-page .payment-refunded {
        background: #e2e3e5;
        color: #41464b;
    }

    .orders-page .view-order-btn {
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 13px;
        white-space: nowrap;
    }

    /* Empty state */
    .orders-page .empty-orders {
        padding: 35px 20px;
        text-align: center;
        background: #f8f9fb;
        border: 1px dashed #dfe3e8;
        border-radius: 12px;
        color: #6c757d;
    }

    /* Pagination */
    .orders-page .pagination {
        gap: 4px;
    }

    .orders-page .pagination .page-link {
        border-radius: 7px;
        font-size: 13px;
        color: #495057;
        border-color: #e1e5e9;
        min-width: 36px;
        text-align: center;
    }

    .orders-page .pagination .active .page-link {
        background: var(--bs-primary, #0d6efd);
        border-color: var(--bs-primary, #0d6efd);
        color: #fff;
    }

    @media (max-width: 767px) {
        .orders-page .filter-panel {
            padding: 15px;
        }

        .orders-page .summary-card .card-body {
            padding: 14px;
        }

        .orders-page .summary-number {
            font-size: 23px;
        }

        .orders-page .orders-table {
            min-width: 850px;
        }
    }
</style>

<div class="panel orders-page">

    <!-- Page Heading -->
    <div class="orders-header">

        <div class="section-title">Orders</div>

        <div class="section-description">
            View and manage customer orders for your business.
        </div>

    </div>

    <!-- Order Summary Cards -->
    <div class="row g-3 mt-2 mb-4">

        <div class="col-6 col-lg">
            <div class="card summary-card summary-total">
                <div class="card-body">
                    <div class="summary-label">Total Orders</div>
                    <div class="summary-number">
                        <?= number_format((int) $orderCounts['total_orders']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg">
            <div class="card summary-card summary-pending">
                <div class="card-body">
                    <div class="summary-label">Pending Orders</div>
                    <div class="summary-number text-warning">
                        <?= number_format((int) $orderCounts['pending_orders']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg">
            <div class="card summary-card summary-accepted">
                <div class="card-body">
                    <div class="summary-label">Accepted Orders</div>
                    <div class="summary-number text-primary">
                        <?= number_format((int) $orderCounts['accepted_orders']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg">
            <div class="card summary-card summary-delivered">
                <div class="card-body">
                    <div class="summary-label">Delivered Orders</div>
                    <div class="summary-number text-success">
                        <?= number_format((int) $orderCounts['delivered_orders']) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg">
            <div class="card summary-card summary-rejected">
                <div class="card-body">
                    <div class="summary-label">Rejected Orders</div>
                    <div class="summary-number text-danger">
                        <?= number_format((int) $orderCounts['rejected_orders']) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Search and Filters -->
    <form action="<?= site_url('orders') ?>" method="get" class="mb-4">

        <?php if ($status !== ''): ?>
            <input type="hidden" name="status" value="<?= esc($status) ?>">
        <?php endif; ?>

        <div class="filter-panel">

            <div class="filter-heading">
                Search &amp; Filter Orders
            </div>

            <div class="row g-3">

                <!-- Search -->
                <div class="col-12">

                    <label class="form-label">Search Orders</label>

                    <input
                        type="text"
                        name="q"
                        class="form-control"
                        placeholder="Search by order number, customer name or mobile number"
                        value="<?= esc($search) ?>"
                    >

                </div>

                <!-- Date Filter -->
                <div class="col-md-3">

                    <label class="form-label">Date Range</label>

                    <select name="date_filter" class="form-select">

                        <option value="" <?= $dateFilter === '' ? 'selected' : '' ?>>
                            All Dates
                        </option>

                        <option value="today" <?= $dateFilter === 'today' ? 'selected' : '' ?>>
                            Today
                        </option>

                        <option value="yesterday" <?= $dateFilter === 'yesterday' ? 'selected' : '' ?>>
                            Yesterday
                        </option>

                        <option value="last7" <?= $dateFilter === 'last7' ? 'selected' : '' ?>>
                            Last 7 Days
                        </option>

                        <option value="last30" <?= $dateFilter === 'last30' ? 'selected' : '' ?>>
                            Last 30 Days
                        </option>

                        <option value="custom" <?= $dateFilter === 'custom' ? 'selected' : '' ?>>
                            Custom Date Range
                        </option>

                    </select>

                </div>

                <!-- From Date -->
                <div class="col-md-3">

                    <label class="form-label">From Date</label>

                    <input
                        type="date"
                        name="from_date"
                        class="form-control"
                        value="<?= esc($fromDate) ?>"
                    >

                </div>

                <!-- To Date -->
                <div class="col-md-3">

                    <label class="form-label">To Date</label>

                    <input
                        type="date"
                        name="to_date"
                        class="form-control"
                        value="<?= esc($toDate) ?>"
                    >

                </div>

                <!-- Payment Status -->
                <div class="col-md-3">

                    <label class="form-label">Payment Status</label>

                    <select name="payment_status" class="form-select">

                        <option value="" <?= $paymentStatus === '' ? 'selected' : '' ?>>
                            All Payment Statuses
                        </option>

                        <option value="pending" <?= $paymentStatus === 'pending' ? 'selected' : '' ?>>
                            Pending
                        </option>

                        <option value="partial" <?= $paymentStatus === 'partial' ? 'selected' : '' ?>>
                            Partially Paid
                        </option>

                        <option value="paid" <?= $paymentStatus === 'paid' ? 'selected' : '' ?>>
                            Paid
                        </option>

                        <option value="refunded" <?= $paymentStatus === 'refunded' ? 'selected' : '' ?>>
                            Refunded
                        </option>

                    </select>

                </div>

            </div>

            <!-- Filter Buttons -->
            <div class="filter-actions d-flex flex-wrap gap-2">

                <button type="submit" class="btn btn-ordzee">
                    Apply Filters
                </button>

                <?php
                $clearParams = [];

                if ($status !== '') {
                    $clearParams['status'] = $status;
                }

                if ($paymentStatus !== '') {
                    $clearParams['payment_status'] = $paymentStatus;
                }

                $clearUrl = site_url('orders');

                if (! empty($clearParams)) {
                    $clearUrl .= '?' . http_build_query($clearParams);
                }
                ?>

                <a href="<?= esc($clearUrl) ?>"
                   class="btn btn-outline-secondary">
                    Clear Search &amp; Date
                </a>

            </div>

        </div>

    </form>

    <!-- Date Error -->
    <?php if (! empty($dateError)): ?>

        <div class="alert alert-danger">
            <?= esc($dateError) ?>
        </div>

    <?php endif; ?>

    <!-- Order Status Filters -->
    <?php
    $filters = [
        ''          => 'All Orders',
        'pending'   => 'Pending',
        'accepted'  => 'Accepted',
        'delivered' => 'Delivered',
        'rejected'  => 'Rejected',
    ];
    ?>

    <div class="status-filter-heading">
        Filter by Order Status
    </div>

    <div class="status-filter">

        <?php foreach ($filters as $value => $label): ?>

            <?php
            $filterParams = [];

            if ($value !== '') {
                $filterParams['status'] = $value;
            }

            if ($search !== '') {
                $filterParams['q'] = $search;
            }

            if ($dateFilter !== '') {
                $filterParams['date_filter'] = $dateFilter;
            }

            if ($dateFilter === 'custom') {
                $filterParams['from_date'] = $fromDate;
                $filterParams['to_date'] = $toDate;
            }

            if ($paymentStatus !== '') {
                $filterParams['payment_status'] = $paymentStatus;
            }

            $filterUrl = site_url('orders');

            if (! empty($filterParams)) {
                $filterUrl .= '?' . http_build_query($filterParams);
            }
            ?>

            <a href="<?= esc($filterUrl) ?>"
               class="btn <?= $status === $value ? 'btn-ordzee' : 'btn-outline-secondary' ?>">

                <?= esc($label) ?>

            </a>

        <?php endforeach; ?>

    </div>

    <!-- Orders Table -->
    <?php if (empty($orders)): ?>

        <div class="empty-orders">

            <div class="fw-semibold mb-2">
                No Orders Found
            </div>

            <div class="small">
                <?php if (
                    $search !== '' ||
                    $dateFilter !== '' ||
                    $status !== '' ||
                    $paymentStatus !== ''
                ): ?>

                    No orders match the selected filters. Try changing or clearing your filters.

                <?php else: ?>

                    You haven't received any orders yet. New customer orders will appear here.

                <?php endif; ?>
            </div>

        </div>

    <?php else: ?>

        <div class="orders-table-wrapper">

            <div class="table-responsive">

                <table class="table table-hover align-middle orders-table">

                    <thead>

                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Order Date</th>
                            <th>Total</th>
                            <th>Order Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($orders as $order): ?>

                            <tr>

                                <td>
                                    <span class="order-number">
                                        <?= esc($order['order_number']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="customer-name">
                                        <?= esc($order['customer_name']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= esc($order['customer_mobile']) ?>
                                </td>

                                <td class="text-nowrap">
                                    <?= esc($order['order_date']) ?>
                                </td>

                                <td>
                                    <span class="order-total">
                                        ₹<?= number_format((float) $order['grand_total'], 2) ?>
                                    </span>
                                </td>

                                <td>

                                    <?php
                                    $currentOrderStatus = strtolower($order['order_status']);

                                    $orderBadgeClass = 'bg-warning text-dark';

                                    if (in_array($currentOrderStatus, ['accepted', 'delivered'], true)) {
                                        $orderBadgeClass = 'bg-success';
                                    } elseif ($currentOrderStatus === 'rejected') {
                                        $orderBadgeClass = 'bg-danger';
                                    }
                                    ?>

                                    <span class="status-badge <?= $orderBadgeClass ?>">
                                        <?= esc(ucfirst($order['order_status'])) ?>
                                    </span>

                                </td>

                                <td>

                                    <?php
                                    $currentPaymentStatus = strtolower($order['payment_status']);

                                    $paymentBadgeClass = 'payment-refunded';

                                    if ($currentPaymentStatus === 'pending') {
                                        $paymentBadgeClass = 'payment-pending';
                                    } elseif ($currentPaymentStatus === 'partial') {
                                        $paymentBadgeClass = 'payment-partial';
                                    } elseif ($currentPaymentStatus === 'paid') {
                                        $paymentBadgeClass = 'payment-paid';
                                    }
                                    ?>

                                    <span class="status-badge <?= $paymentBadgeClass ?>">
                                        <?= esc(ucfirst($order['payment_status'])) ?>
                                    </span>

                                </td>

                                <td>

                                    <a href="<?= site_url('orders/view/' . $order['id']) ?>"
                                       class="btn btn-sm btn-ordzee view-order-btn">
                                        View
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- Pagination -->
        <?php
        $currentPage = $pager->getCurrentPage();
        $totalPages = $pager->getPageCount();

        $paginationParams = [];

        if ($status !== '') {
            $paginationParams['status'] = $status;
        }

        if ($search !== '') {
            $paginationParams['q'] = $search;
        }

        if ($dateFilter !== '') {
            $paginationParams['date_filter'] = $dateFilter;
        }

        if ($dateFilter === 'custom') {
            $paginationParams['from_date'] = $fromDate;
            $paginationParams['to_date'] = $toDate;
        }

        if ($paymentStatus !== '') {
            $paginationParams['payment_status'] = $paymentStatus;
        }
        ?>

        <?php if ($totalPages > 1): ?>

            <nav class="mt-4" aria-label="Orders pagination">

                <ul class="pagination justify-content-center flex-wrap">

                    <?php
                    $paginationParams['page'] = max(1, $currentPage - 1);

                    $previousUrl = site_url('orders') . '?' . http_build_query($paginationParams);
                    ?>

                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">

                        <a class="page-link" href="<?= esc($previousUrl) ?>">
                            Previous
                        </a>

                    </li>

                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>

                        <?php
                        $paginationParams['page'] = $page;

                        $pageUrl = site_url('orders') . '?' . http_build_query($paginationParams);
                        ?>

                        <li class="page-item <?= $currentPage === $page ? 'active' : '' ?>">

                            <a class="page-link" href="<?= esc($pageUrl) ?>">
                                <?= $page ?>
                            </a>

                        </li>

                    <?php endfor; ?>

                    <?php
                    $paginationParams['page'] = min($totalPages, $currentPage + 1);

                    $nextUrl = site_url('orders') . '?' . http_build_query($paginationParams);
                    ?>

                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">

                        <a class="page-link" href="<?= esc($nextUrl) ?>">
                            Next
                        </a>

                    </li>

                </ul>

            </nav>

        <?php endif; ?>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>