<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<div class="panel">

    <div class="section-title">Order Details</div>

    <div class="section-description">
        Complete information about this customer order.
    </div>

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

    <!-- Order Action Buttons -->
    <div class="mt-3 mb-3 d-flex flex-column align-items-start gap-2">

        <a href="<?= site_url('orders') ?>" class="btn btn-secondary">
            Back to Orders
        </a>

        <?php if (in_array($order['order_status'], ['accepted', 'preparing', 'ready'], true)): ?>

            <form method="post"
                action="<?= site_url('orders/deliver/' . $order['id']) ?>"
                onsubmit="return confirm('Are you sure you want to mark this order as delivered?');">

                <?= csrf_field() ?>

                <button type="submit" class="btn btn-primary">
                    Mark as Delivered
                </button>

            </form>

        <?php endif; ?>

    </div>

    <!-- Accept Order -->
    <?php if ($order['order_status'] === 'pending'): ?>

        <button type="button"
            class="btn btn-success mb-3"
            data-bs-toggle="modal"
            data-bs-target="#acceptOrderModal">
            Accept Order
        </button>

        <div class="modal fade" id="acceptOrderModal" tabindex="-1"
            aria-labelledby="acceptOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/accept/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h5 class="modal-title" id="acceptOrderModalLabel">
                                Accept Order
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p>Are you sure you want to accept this order?</p>

                            <label class="form-label fw-bold">
                                Message to Customer *
                            </label>

                            <textarea
                                name="customer_message"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                required
                                placeholder="Example: Your order will be delivered today. Thank you for ordering with us!"></textarea>

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

    <?php endif; ?>

    <!-- Reject Order -->
    <?php if ($order['order_status'] === 'pending'): ?>

        <button type="button"
            class="btn btn-danger mb-3 ms-2"
            data-bs-toggle="modal"
            data-bs-target="#rejectOrderModal">
            Reject Order
        </button>

        <div class="modal fade" id="rejectOrderModal" tabindex="-1"
            aria-labelledby="rejectOrderModalLabel" aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="post"
                        action="<?= site_url('orders/reject/' . $order['id']) ?>">

                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectOrderModalLabel">
                                Reject Order
                            </h5>

                            <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p>Are you sure you want to reject this order?</p>

                            <label class="form-label fw-bold">
                                Rejection Reason / Message to Customer *
                            </label>

                            <textarea
                                name="customer_message"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                required
                                placeholder="Example: Sorry, the requested items are currently unavailable."></textarea>

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

    <!-- Order Information -->
    <h5 class="mt-4">Order Information</h5>

    <div class="table-responsive">
        <table class="table table-bordered">

            <tr>
                <th>Order Number</th>
                <td><?= esc($order['order_number']) ?></td>
            </tr>

            <tr>
                <th>Order Date</th>
                <td><?= esc($order['order_date']) ?></td>
            </tr>

            <tr>
                <th>Order Status</th>
                <td>
                    <span class="badge <?=
                        in_array(strtolower($order['order_status']), ['accepted', 'delivered'], true)
                            ? 'bg-success'
                            : ($order['order_status'] === 'rejected'
                                ? 'bg-danger'
                                : 'bg-warning text-dark')
                    ?>">
                        <?= esc(ucfirst($order['order_status'])) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th>Payment Status</th>
                <td><?= esc(ucfirst($order['payment_status'])) ?></td>
            </tr>

        </table>
    </div>

    <!-- Customer Information -->
    <h5 class="mt-4">Customer Information</h5>

    <div class="table-responsive">
        <table class="table table-bordered">

            <tr>
                <th>Customer Name</th>
                <td><?= esc($order['customer_name']) ?></td>
            </tr>

            <tr>
                <th>Mobile Number</th>
                <td><?= esc($order['customer_mobile']) ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= esc($order['customer_email'] ?? 'Not provided') ?></td>
            </tr>

            <tr>
                <th>Delivery Address</th>
                <td><?= nl2br(esc($order['delivery_address'])) ?></td>
            </tr>

        </table>
    </div>

    <!-- Ordered Items -->
    <h5 class="mt-4">Ordered Items</h5>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">

            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Item Total</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($items as $item): ?>

                    <tr>
                        <td><?= esc($item['product_name']) ?></td>
                        <td><?= esc($item['unit']) ?></td>
                        <td><?= esc($item['quantity']) ?></td>

                        <td>
                            ₹<?= number_format((float) $item['unit_price'], 2) ?>
                        </td>

                        <td>
                            ₹<?= number_format((float) $item['item_total'], 2) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>
    </div>

    <!-- Order Summary -->
    <h5 class="mt-4">Order Summary</h5>

    <div class="table-responsive">
        <table class="table table-bordered">

            <tr>
                <th>Subtotal</th>
                <td class="text-end">
                    ₹<?= number_format((float) $order['subtotal'], 2) ?>
                </td>
            </tr>

            <tr>
                <th>Discount</th>
                <td class="text-end">
                    ₹<?= number_format((float) $order['discount'], 2) ?>
                </td>
            </tr>

            <tr>
                <th>Delivery Charge</th>
                <td class="text-end">
                    ₹<?= number_format((float) $order['delivery_charge'], 2) ?>
                </td>
            </tr>

            <tr>
                <th>Grand Total</th>
                <td class="text-end fw-bold">
                    ₹<?= number_format((float) $order['grand_total'], 2) ?>
                </td>
            </tr>

            <tr>
                <th>Amount Paid</th>
                <td class="text-end">
                    ₹<?= number_format((float) $order['amount_paid'], 2) ?>
                </td>
            </tr>

            <tr>
                <th>Balance Amount</th>
                <td class="text-end">
                    ₹<?= number_format((float) $order['balance_amount'], 2) ?>
                </td>
            </tr>

        </table>
    </div>

</div>

<?= $this->endSection() ?>