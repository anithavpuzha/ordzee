<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<?php
$currentOrderStatus = strtolower($order['order_status'] ?? 'pending');
$currentPaymentStatus = strtolower($order['payment_status'] ?? 'pending');
$statusHistory = $statusHistory ?? [];

$paymentAllowedStatuses = [
    'accepted',
    'preparing',
    'ready',
    'delivered',
];

$canRecordPayment = in_array(
    $currentOrderStatus,
    $paymentAllowedStatuses,
    true
);

$canRecordRefund = in_array(
    $currentOrderStatus,
    $paymentAllowedStatuses,
    true
);

$amountPaid = (float) ($order['amount_paid'] ?? 0);
$balanceAmount = (float) ($order['balance_amount'] ?? 0);
$grandTotal = (float) ($order['grand_total'] ?? 0);

$payments = $payments ?? [];

$paymentMethods = [
    'cash'          => 'Cash',
    'upi'           => 'UPI',
    'bank_transfer' => 'Bank Transfer',
    'other'         => 'Other',
];

// Order status badge
$orderBadgeClass = 'bg-warning text-dark';

if (in_array($currentOrderStatus, ['accepted', 'delivered'], true)) {
    $orderBadgeClass = 'bg-success';
} elseif ($currentOrderStatus === 'rejected') {
    $orderBadgeClass = 'bg-danger';
} elseif (in_array($currentOrderStatus, ['preparing', 'ready'], true)) {
    $orderBadgeClass = 'bg-info text-dark';
}

// Payment status badge
$paymentBadgeClass = 'bg-warning text-dark';

if ($currentPaymentStatus === 'paid') {
    $paymentBadgeClass = 'bg-success';
} elseif ($currentPaymentStatus === 'partial') {
    $paymentBadgeClass = 'bg-info text-dark';
} elseif ($currentPaymentStatus === 'refunded') {
    $paymentBadgeClass = 'bg-secondary';
}

$itemCount = count($items ?? []);

// Calculate payment and refund totals
$totalPayments = 0;
$totalRefunds = 0;

foreach ($payments as $payment) {
    $transactionType = $payment['transaction_type'] ?? '';

    if ($transactionType === 'payment') {
        $totalPayments += (float) ($payment['amount'] ?? 0);
    } elseif ($transactionType === 'refund') {
        $totalRefunds += (float) ($payment['amount'] ?? 0);
    }
}
?>

<style>
    .order-details-page {
        --od-border: #e8edf3;
        --od-muted: #718096;
        --od-heading: #263445;
    }

    .order-details-page .section-description {
        color: var(--od-muted);
    }

    /* Main order header */

    .order-details-page .order-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px;
        margin-top: 22px;
        background: #f8fafc;
        border: 1px solid var(--od-border);
        border-radius: 12px;
    }

    .order-details-page .order-number {
        font-size: 21px;
        font-weight: 700;
        color: var(--od-heading);
        overflow-wrap: anywhere;
    }

    .order-details-page .order-date {
        color: var(--od-muted);
        font-size: 13px;
        margin-top: 5px;
    }

    .order-details-page .status-label {
        font-size: 11px;
        color: var(--od-muted);
        margin-bottom: 6px;
        font-weight: 600;
    }

    .order-details-page .status-badge {
        display: inline-block;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 650;
        white-space: nowrap;
    }

    /* Action area */

    .order-details-page .action-panel {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 9px;
        margin-top: 16px;
    }

    .order-details-page .action-panel .btn {
        font-weight: 600;
        border-radius: 7px;
    }

    /* Detail sections */

    .order-details-page .detail-section {
        background: #fff;
        border: 1px solid var(--od-border);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }

    .order-details-page .detail-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 16px;
        font-weight: 700;
        color: var(--od-heading);
        margin-bottom: 17px;
    }

    .order-details-page .detail-heading i {
        color: var(--od-muted);
        font-size: 17px;
    }

    /* Customer information and payment cards */

    .order-details-page .info-card {
        height: 100%;
        padding: 14px;
        border: 1px solid var(--od-border);
        border-radius: 9px;
        background: #fff;
    }

    .order-details-page .info-label {
        font-size: 12px;
        color: var(--od-muted);
        margin-bottom: 6px;
        font-weight: 500;
    }

    .order-details-page .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--od-heading);
        overflow-wrap: anywhere;
    }

    .order-details-page .address-card {
        background: #f8fafc;
    }

    /* Tables */

    .order-details-page .items-table {
        margin-bottom: 0;
    }

    .order-details-page .items-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
        padding: 13px 12px;
        border-color: var(--od-border);
    }

    .order-details-page .items-table tbody td {
        padding: 13px 12px;
        vertical-align: middle;
        border-color: var(--od-border);
        font-size: 13px;
    }

    .order-details-page .product-name {
        font-weight: 650;
        color: var(--od-heading);
    }

    .order-details-page .price-cell {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    /* Summary */

    .order-details-page .summary-table {
        margin-bottom: 0;
    }

    .order-details-page .summary-table th,
    .order-details-page .summary-table td {
        padding: 12px 14px;
        border-color: var(--od-border);
    }

    .order-details-page .summary-table th {
        font-weight: 500;
        color: #526071;
    }

    .order-details-page .summary-table td {
        text-align: right;
        font-weight: 550;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .order-details-page .grand-total-row th,
    .order-details-page .grand-total-row td {
        background: #f8fafc;
        font-size: 16px;
        font-weight: 750;
        color: var(--od-heading);
    }

    .order-details-page .balance-row th,
    .order-details-page .balance-row td {
        font-weight: 750;
    }

    .order-details-page .empty-items {
        padding: 25px;
        text-align: center;
        color: var(--od-muted);
    }

    /* Modals */

    .order-details-page .modal-content {
        border-radius: 12px;
        border: 0;
    }

    .order-details-page .modal-header,
    .order-details-page .modal-footer {
        border-color: var(--od-border);
    }

    @media (max-width: 576px) {
        .order-details-page .order-header {
            padding: 15px;
        }

        .order-details-page .order-number {
            font-size: 18px;
        }

        .order-details-page .detail-section {
            padding: 14px;
        }

        .order-details-page .action-panel {
            display: grid;
            grid-template-columns: 1fr;
        }

        .order-details-page .action-panel .btn {
            width: 100%;
        }

        .order-details-page .items-table thead th,
        .order-details-page .items-table tbody td {
            padding: 10px 9px;
        }
    }

    /* Order Status Timeline */

    .order-timeline {
        position: relative;
        padding-left: 8px;
    }

    .order-timeline-item {
        position: relative;
        display: flex;
        gap: 15px;
        padding-bottom: 25px;
    }

    .order-timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 12px;
        top: 27px;
        bottom: 0;
        width: 2px;
        background: #e8edf3;
    }

    .order-timeline-marker {
        position: relative;
        z-index: 1;
        width: 26px;
        height: 26px;
        min-width: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        font-size: 16px;
    }

    .order-timeline-content {
        flex: 1;
        min-width: 0;
        padding-top: 3px;
    }
</style>

<div class="panel order-details-page">

    <!-- Page Heading -->
    <div class="section-title">Order Details</div>

    <div class="section-description">
        View customer information, ordered items and payment details.
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success mt-3">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-3">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <!-- Order Actions -->
    <div class="action-panel">

        <a href="<?= site_url('orders') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Orders
        </a>

        <?php if (in_array($currentOrderStatus, ['accepted', 'preparing', 'ready'], true)): ?>

            <form method="post"
                action="<?= site_url('orders/deliver/' . $order['id']) ?>"
                onsubmit="return confirm('Are you sure you want to mark this order as delivered?');">

                <?= csrf_field() ?>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>
                    Mark as Delivered
                </button>

            </form>

        <?php endif; ?>

        <?php if ($currentOrderStatus === 'pending'): ?>

            <button type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#acceptOrderModal">

                <i class="bi bi-check-lg me-1"></i>
                Accept Order

            </button>

            <button type="button"
                class="btn btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#rejectOrderModal">

                <i class="bi bi-x-lg me-1"></i>
                Reject Order

            </button>

        <?php endif; ?>

    </div>

    <!-- Order Overview -->
    <div class="order-header">

        <div>
            <div class="status-label">ORDER NUMBER</div>

            <div class="order-number">
                <?= esc($order['order_number']) ?>
            </div>

            <div class="order-date">
                <i class="bi bi-calendar3 me-1"></i>
                <?= esc($order['order_date']) ?>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3">

            <div>
                <div class="status-label">ORDER STATUS</div>

                <span class="status-badge <?= $orderBadgeClass ?>">
                    <?= esc(ucfirst($currentOrderStatus)) ?>
                </span>
            </div>

            <div>
                <div class="status-label">PAYMENT STATUS</div>

                <span class="status-badge <?= $paymentBadgeClass ?>">
                    <?= esc(ucfirst($currentPaymentStatus)) ?>
                </span>
            </div>

        </div>

    </div>

    <!-- Accept and Reject Modals -->
    <?php if ($currentOrderStatus === 'pending'): ?>

        <!-- Accept Order Modal -->
        <div class="modal fade" id="acceptOrderModal" tabindex="-1"
            aria-labelledby="acceptOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/accept/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">

                            <h5 class="modal-title" id="acceptOrderModalLabel">
                                Accept Order
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        </div>

                        <div class="modal-body">

                            <p>
                                Are you sure you want to accept this order?
                            </p>

                            <label class="form-label fw-bold">
                                Message to Customer *
                            </label>

                            <textarea
                                name="customer_message"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                required><?= esc('Thank you for placing your order! We will keep you updated about your order.') ?></textarea>

                            <small class="text-muted">
                                You can edit this prepared message or add your own message before continuing to WhatsApp.
                            </small>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-success">
                                Accept & Continue to WhatsApp
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Reject Order Modal -->
        <div class="modal fade" id="rejectOrderModal" tabindex="-1"
            aria-labelledby="rejectOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/reject/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">

                            <h5 class="modal-title" id="rejectOrderModalLabel">
                                Reject Order
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        </div>

                        <div class="modal-body">

                            <p>
                                Are you sure you want to reject this order?
                            </p>

                            <label class="form-label fw-bold">
                                Rejection Reason / Message to Customer *
                            </label>

                            <textarea
                                name="customer_message"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                required><?= esc('We are sorry, but we are unable to fulfil your order at the moment. We apologise for the inconvenience.') ?></textarea>

                            <small class="text-muted">
                                You can edit this prepared message and add your own rejection reason before continuing to WhatsApp.
                            </small>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-danger">
                                Reject & Continue to WhatsApp
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!-- Order Status History -->
    <div class="detail-section">

        <div class="detail-heading">
            <i class="bi bi-clock-history"></i>
            Order Status History
        </div>

        <?php if (! empty($statusHistory)): ?>

            <div class="order-timeline">

                <?php foreach ($statusHistory as $index => $history): ?>

                    <?php
                    $historyStatus = strtolower($history['new_status'] ?? '');

                    $historyIcon = 'bi-circle';
                    $historyColor = 'secondary';

                    switch ($historyStatus) {
                        case 'pending':
                            $historyIcon = 'bi-cart-check';
                            $historyColor = 'warning';
                            break;

                        case 'accepted':
                            $historyIcon = 'bi-check-circle-fill';
                            $historyColor = 'success';
                            break;

                        case 'preparing':
                            $historyIcon = 'bi-hourglass-split';
                            $historyColor = 'info';
                            break;

                        case 'ready':
                            $historyIcon = 'bi-box-seam';
                            $historyColor = 'primary';
                            break;

                        case 'delivered':
                            $historyIcon = 'bi-truck';
                            $historyColor = 'success';
                            break;

                        case 'rejected':
                            $historyIcon = 'bi-x-circle-fill';
                            $historyColor = 'danger';
                            break;
                    }
                    ?>

                    <div class="order-timeline-item">

                        <div class="order-timeline-marker text-<?= $historyColor ?>">
                            <i class="bi <?= $historyIcon ?>"></i>
                        </div>

                        <div class="order-timeline-content">

                            <div class="d-flex justify-content-between
                            align-items-start flex-wrap gap-2">

                                <div class="fw-semibold">
                                    <?php if ($historyStatus === 'pending'): ?>
                                        Order Placed
                                    <?php else: ?>
                                        <?= esc(ucfirst($historyStatus)) ?>
                                    <?php endif; ?>
                                </div>

                                <small class="text-muted">
                                    <?= esc(date(
                                        'd M Y, h:i A',
                                        strtotime($history['created_at'])
                                    )) ?>
                                </small>

                            </div>

                            <?php if (! empty($history['remarks'])): ?>

                                <div class="text-muted small mt-1">
                                    <?= nl2br(esc($history['remarks'])) ?>
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="text-muted small">
                No order status history is available.
            </div>

        <?php endif; ?>

    </div>



    <!-- Customer Information -->
    <div class="detail-section">

        <div class="detail-heading">
            <i class="bi bi-person"></i>
            Customer Information
        </div>

        <div class="row g-4">

            <!-- Customer Name -->
            <div class="col-md-4">

                <div class="info-label">CUSTOMER NAME</div>

                <div class="info-value">
                    <?= esc($order['customer_name']) ?>
                </div>

            </div>

            <!-- Mobile Number -->
            <div class="col-md-4">

                <div class="info-label">MOBILE NUMBER</div>

                <div class="info-value">
                    <i class="bi bi-telephone me-1 text-muted"></i>
                    <a href="tel:<?= esc($order['customer_mobile'], 'attr') ?>"
                        class="text-decoration-none text-reset">
                        <?= esc($order['customer_mobile']) ?>
                    </a>
                </div>

            </div>

            <!-- Email Address -->
            <div class="col-md-4">

                <div class="info-label">EMAIL ADDRESS</div>

                <div class="info-value">
                    <i class="bi bi-envelope me-1 text-muted"></i>
                    <?= esc($order['customer_email'] ?: 'Not provided') ?>
                </div>

            </div>

            <!-- Delivery Address -->
            <div class="col-12">

                <div class="pt-3 border-top">

                    <div class="info-label mb-2">DELIVERY ADDRESS</div>

                    <div class="info-value fw-normal">
                        <i class="bi bi-geo-alt me-1 text-muted"></i>
                        <?= nl2br(esc($order['delivery_address'])) ?>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Ordered Items -->
    <div class="detail-section">

        <div class="detail-heading">
            <i class="bi bi-basket"></i>
            Ordered Items

            <span class="text-muted small fw-normal">
                (<?= $itemCount ?>)
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-bordered items-table">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Item Total</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (! empty($items)): ?>

                        <?php foreach ($items as $item): ?>

                            <tr>

                                <td>
                                    <span class="product-name">
                                        <?= esc($item['product_name']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= esc($item['unit']) ?>
                                </td>

                                <td class="text-end">
                                    <?= esc($item['quantity']) ?>
                                </td>

                                <td class="text-end price-cell">
                                    ₹<?= number_format((float) $item['unit_price'], 2) ?>
                                </td>

                                <td class="text-end price-cell fw-semibold">
                                    ₹<?= number_format((float) $item['item_total'], 2) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="5" class="empty-items">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                No ordered items found.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- Order Summary -->
    <div class="detail-section">

        <div class="detail-heading">
            <i class="bi bi-calculator"></i>
            Order Summary
        </div>

        <div class="row justify-content-end">

            <div class="col-lg-7 col-md-9">

                <div class="table-responsive">

                    <table class="table table-bordered summary-table">

                        <tbody>

                            <tr>
                                <th>Subtotal</th>

                                <td>
                                    ₹<?= number_format((float) $order['subtotal'], 2) ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Discount</th>

                                <td>
                                    ₹<?= number_format((float) $order['discount'], 2) ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Delivery Charge</th>

                                <td>
                                    ₹<?= number_format((float) $order['delivery_charge'], 2) ?>
                                </td>
                            </tr>

                            <tr class="grand-total-row">
                                <th>Grand Total</th>

                                <td>
                                    ₹<?= number_format($grandTotal, 2) ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Amount Paid</th>

                                <td>
                                    ₹<?= number_format($amountPaid, 2) ?>
                                </td>
                            </tr>

                            <tr class="balance-row">
                                <th>Balance Amount</th>

                                <td>
                                    ₹<?= number_format($balanceAmount, 2) ?>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- Payment Management -->
    <div class="detail-section">

        <div class="detail-heading">
            <i class="bi bi-wallet2"></i>
            Payment Management
        </div>

        <!-- Payment Summary -->
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-label">Order Total</div>

                    <div class="info-value fs-5">
                        ₹<?= number_format($grandTotal, 2) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-label">Net Amount Paid</div>

                    <div class="info-value fs-5 text-success">
                        ₹<?= number_format($amountPaid, 2) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-label">Outstanding Balance</div>

                    <div class="info-value fs-5 <?= $balanceAmount > 0 ? 'text-danger' : 'text-success' ?>">
                        ₹<?= number_format($balanceAmount, 2) ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Totals -->
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-label">Total Payments Recorded</div>

                    <div class="info-value">
                        ₹<?= number_format($totalPayments, 2) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-label">Total Refunds Recorded</div>

                    <div class="info-value">
                        ₹<?= number_format($totalRefunds, 2) ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Actions -->
        <div class="d-flex flex-wrap gap-2 mb-4">

            <?php if ($canRecordPayment && $balanceAmount > 0): ?>

                <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#recordPaymentModal">

                    <i class="bi bi-plus-circle me-1"></i>
                    Record Payment

                </button>

            <?php endif; ?>

            <?php if ($canRecordRefund && $amountPaid > 0): ?>

                <button type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#recordRefundModal">

                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Record Refund

                </button>

            <?php endif; ?>

            <?php if (! $canRecordPayment): ?>

                <span class="text-muted small align-self-center">
                    Payment actions are available after an order is accepted.
                </span>

            <?php endif; ?>

        </div>

        <!-- Record Payment Modal -->
        <div class="modal fade" id="recordPaymentModal" tabindex="-1"
            aria-labelledby="recordPaymentModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/record-payment/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">

                            <h5 class="modal-title" id="recordPaymentModalLabel">
                                Record Payment
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        </div>

                        <div class="modal-body">

                            <div class="alert alert-light border">
                                Outstanding balance:

                                <strong>
                                    ₹<?= number_format($balanceAmount, 2) ?>
                                </strong>
                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Payment Amount *
                                </label>

                                <input type="number"
                                    name="amount"
                                    class="form-control"
                                    min="0.01"
                                    max="<?= esc(number_format($balanceAmount, 2, '.', '')) ?>"
                                    step="0.01"
                                    required
                                    placeholder="Enter amount">

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Payment Method *
                                </label>

                                <select name="payment_method"
                                    class="form-select" required>

                                    <option value="">
                                        Select payment method
                                    </option>

                                    <?php foreach ($paymentMethods as $value => $label): ?>

                                        <option value="<?= esc($value) ?>">
                                            <?= esc($label) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Payment Date *
                                </label>

                                <input type="datetime-local"
                                    name="payment_date"
                                    class="form-control"
                                    value="<?= esc(date('Y-m-d\TH:i')) ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Reference Number
                                </label>

                                <input type="text"
                                    name="reference_number"
                                    class="form-control"
                                    maxlength="150"
                                    placeholder="Optional transaction ID">

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Notes
                                </label>

                                <textarea name="notes"
                                    class="form-control"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Optional payment notes"></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-success">
                                Save Payment
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Record Refund Modal -->
        <div class="modal fade" id="recordRefundModal" tabindex="-1"
            aria-labelledby="recordRefundModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/record-refund/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">

                            <h5 class="modal-title" id="recordRefundModalLabel">
                                Record Refund
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        </div>

                        <div class="modal-body">

                            <div class="alert alert-light border">
                                Maximum refundable amount:

                                <strong>
                                    ₹<?= number_format($amountPaid, 2) ?>
                                </strong>
                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Refund Amount *
                                </label>

                                <input type="number"
                                    name="amount"
                                    class="form-control"
                                    min="0.01"
                                    max="<?= esc(number_format($amountPaid, 2, '.', '')) ?>"
                                    step="0.01"
                                    required
                                    placeholder="Enter refund amount">

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Refund Method *
                                </label>

                                <select name="payment_method"
                                    class="form-select" required>

                                    <option value="">
                                        Select refund method
                                    </option>

                                    <?php foreach ($paymentMethods as $value => $label): ?>

                                        <option value="<?= esc($value) ?>">
                                            <?= esc($label) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Refund Date *
                                </label>

                                <input type="datetime-local"
                                    name="payment_date"
                                    class="form-control"
                                    value="<?= esc(date('Y-m-d\TH:i')) ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Reference Number
                                </label>

                                <input type="text"
                                    name="reference_number"
                                    class="form-control"
                                    maxlength="150"
                                    placeholder="Optional refund reference">

                            </div>

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Refund Notes
                                </label>

                                <textarea name="notes"
                                    class="form-control"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Reason for refund"></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-danger">
                                Save Refund
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Payment History -->
        <div class="detail-heading mt-4">
            <i class="bi bi-clock-history"></i>
            Payment History
        </div>

        <div class="table-responsive">

            <table class="table table-bordered items-table">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th>Notes</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (! empty($payments)): ?>

                        <?php foreach ($payments as $payment): ?>

                            <?php
                            $isRefund = ($payment['transaction_type'] ?? '') === 'refund';
                            $method = $payment['payment_method'] ?? 'other';
                            ?>

                            <tr>

                                <td class="text-nowrap">
                                    <?= esc($payment['payment_date'] ?? '—') ?>
                                </td>

                                <td>

                                    <?php if ($isRefund): ?>

                                        <span class="badge bg-danger">
                                            Refund
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-success">
                                            Payment
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <?= esc($paymentMethods[$method] ?? ucfirst($method)) ?>
                                </td>

                                <td>
                                    <?= esc(($payment['reference_number'] ?? '') ?: '—') ?>
                                </td>

                                <td>
                                    <?= esc(($payment['notes'] ?? '') ?: '—') ?>
                                </td>

                                <td class="text-end text-nowrap fw-semibold <?= $isRefund ? 'text-danger' : 'text-success' ?>">

                                    <?= $isRefund ? '− ' : '+ ' ?>₹<?= number_format((float) ($payment['amount'] ?? 0), 2) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="empty-items">
                                <i class="bi bi-wallet2 fs-4 d-block mb-2"></i>
                                No payment transactions recorded yet.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>