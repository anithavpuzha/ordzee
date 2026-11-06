<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<!-- Menu information -->
<section class="panel mb-4">

    <div class="d-flex justify-content-between align-items-start gap-3">

        <div>
            <h2 class="section-title">
                <?= esc($menu['menu_title']) ?>
            </h2>

            <p class="section-description mb-2">
                Menu Date:
                <?= esc(date('d M Y', strtotime($menu['menu_date']))) ?>
            </p>

            <span class="badge bg-secondary">
                <?= esc(ucfirst($menu['status'])) ?>
            </span>
        </div>

        <a href="<?= site_url('daily-menu') ?>"
           class="btn btn-outline-secondary btn-sm">
            Back to Menus
        </a>

    </div>

</section>


<!-- Menu items -->
<section class="panel">

    <div class="mb-3">
        <h2 class="section-title">Menu Items</h2>

        <p class="section-description mb-0">
            Items added to this daily menu will appear here.
        </p>
    </div>

    <?php if (empty($items)): ?>

        <div class="text-center py-5">

            <div class="mb-3" style="font-size:38px;">▦</div>

            <h5 class="fw-bold">No items added yet</h5>

            <p class="muted mb-0">
                Start adding products to this daily menu.
            </p>

        </div>

    <?php else: ?>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Availability</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($items as $item): ?>

                        <tr>
                            <td><?= esc($item['product_name']) ?></td>

                            <td><?= esc($item['unit']) ?></td>

                            <td>
                                ₹<?= esc(number_format((float) $item['price'], 2)) ?>
                            </td>

                            <td>
                                <?= $item['is_available'] ? 'Available' : 'Unavailable' ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>

<?= $this->endSection() ?>