<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
    <h1 class="fw-bold">
        <?= esc($business['business_name']) ?>
    </h1>

    <?php if (! empty($business['address'])): ?>
        <p class="text-muted mb-0">
            <?= esc($business['address']) ?>
        </p>
    <?php endif; ?>

    <h4 class="mt-3">Menu</h4>
</div>

<?php if (empty($items)): ?>

    <div class="alert alert-info text-center">
        No menu items are currently available.
    </div>

<?php else: ?>

    <div class="row g-3">

        <?php foreach ($items as $item): ?>

            <div class="col-12 col-md-6">

                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h5 class="card-title mb-1">
                                <?= esc($item['product_name']) ?>
                            </h5>

                            <p class="text-muted mb-0">
                                <?= esc($item['unit']) ?>
                            </p>
                        </div>

                        <div class="fw-bold fs-5">
                            ₹<?= number_format((float) $item['price'], 2) ?>
                        </div>

                    </div>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?= $this->endSection() ?>