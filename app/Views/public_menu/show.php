<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

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
    <p class="text-muted">Select the quantities you want to order.</p>
</div>

<?php if (empty($items)): ?>

    <div class="alert alert-info text-center">
        No menu items are currently available.
    </div>

<?php else: ?>

    <form method="post"
          action="<?= site_url('menu/' . $business['slug'] . '/place-order') ?>">

        <?= csrf_field() ?>

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

                                <div class="fw-bold mt-2">
                                    ₹<?= number_format((float) $item['price'], 2) ?>
                                </div>
                            </div>

                            <div style="width: 100px;">
                                <label class="form-label">Quantity</label>

                                <input
                                    type="number"
                                    name="quantities[<?= esc($item['id']) ?>]"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="0"
                                >
                            </div>

                        </div>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">

                <h5 class="mb-3">Your Details</h5>

                <div class="mb-3">
                    <label class="form-label">Customer Name *</label>
                    <input
                        type="text"
                        name="customer_name"
                        class="form-control"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile Number *</label>
                    <input
                        type="tel"
                        name="customer_mobile"
                        class="form-control"
                        maxlength="20"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (Optional)</label>
                    <input
                        type="email"
                        name="customer_email"
                        class="form-control"
                        maxlength="150"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Delivery Address *</label>
                    <textarea
                        name="delivery_address"
                        class="form-control"
                        rows="3"
                        required
                    ></textarea>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Continue to WhatsApp
                </button>

            </div>
        </div>

    </form>

<?php endif; ?>

<?= $this->endSection() ?>