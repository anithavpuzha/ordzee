<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<div class="panel">

    <div class="section-title">Orders</div>

    <div class="section-description">
        View and manage customer orders for your business.
    </div>

    <?php
    $filters = [
        ''          => 'All Orders',
        'pending'   => 'Pending',
        'accepted'  => 'Accepted',
        'delivered' => 'Delivered',
        'rejected'  => 'Rejected',
    ];
    ?>

    <div class="d-flex flex-wrap gap-2 mt-3 mb-3">

        <?php foreach ($filters as $value => $label): ?>

            <a href="<?= site_url('orders') . ($value !== '' ? '?status=' . urlencode($value) : '') ?>"
               class="btn <?= $status === $value ? 'btn-ordzee' : 'btn-outline-secondary' ?>">

                <?= esc($label) ?>

            </a>

        <?php endforeach; ?>

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
                            <td><?= esc($order['order_number']) ?></td>

                            <td><?= esc($order['customer_name']) ?></td>

                            <td><?= esc($order['customer_mobile']) ?></td>

                            <td><?= esc($order['order_date']) ?></td>

                            <td>
                                ₹<?= number_format((float) $order['grand_total'], 2) ?>
                            </td>

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

                            <td><?= esc(ucfirst($order['payment_status'])) ?></td>

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

        <?php
        $currentPage = $pager->getCurrentPage();
        $totalPages  = $pager->getPageCount();
        ?>

        <?php if ($totalPages > 1): ?>

            <nav class="mt-4" aria-label="Orders pagination">

                <ul class="pagination justify-content-center flex-wrap">

                    <?php
                    $previousUrl = site_url('orders') . '?page=' . max(1, $currentPage - 1)
                        . ($status !== '' ? '&status=' . urlencode($status) : '');

                    $nextUrl = site_url('orders') . '?page=' . min($totalPages, $currentPage + 1)
                        . ($status !== '' ? '&status=' . urlencode($status) : '');
                    ?>

                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= esc($previousUrl) ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>

                        <?php
                        $pageUrl = site_url('orders') . '?page=' . $page
                            . ($status !== '' ? '&status=' . urlencode($status) : '');
                        ?>

                        <li class="page-item <?= $currentPage === $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= esc($pageUrl) ?>">
                                <?= $page ?>
                            </a>
                        </li>

                    <?php endfor; ?>

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