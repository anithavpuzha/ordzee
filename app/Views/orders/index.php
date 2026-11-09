<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<div class="panel">

    <div class="section-title">Orders</div>

    <div class="section-description">
        View and manage customer orders for your business.
    </div>

    <?php if (empty($orders)): ?>

        <div class="alert alert-info mt-3">
            No orders received yet.
        </div>

    <?php else: ?>

        <div class="table-responsive mt-3">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
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
                                <?= esc($order['order_number']) ?>
                            </td>

                            <td>
                                <?= esc($order['customer_name']) ?>
                            </td>

                            <td>
                                <?= esc($order['customer_mobile']) ?>
                            </td>

                            <td>
                                <?= esc($order['order_date']) ?>
                            </td>

                            <td>
                                ₹<?= number_format((float) $order['grand_total'], 2) ?>
                            </td>

                            <td>
                                <span class="badge <?=
                                                    in_array(strtolower($order['order_status']), ['accepted', 'delivered']) ? 'bg-success' : ($order['order_status'] === 'rejected' ? 'bg-danger' : 'bg-warning text-dark')
                                                    ?>">
                                    <?= esc(ucfirst($order['order_status'])) ?>
                                </span>
                            </td>

                            <td>
                                <?= esc(ucfirst($order['payment_status'])) ?>
                            </td>

                            <td>
                                <a href="<?= site_url('orders/view/' . $order['id']) ?>"
                                    class="btn btn-sm btn-ordzee">
                                    View
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>