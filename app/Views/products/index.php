<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<!-- Business information -->
<section class="panel mb-4">

    <h2 class="section-title">
        <?= esc($business['business_name']) ?>
    </h2>

    <p class="section-description mb-0">
        Manage your reusable product catalogue here.
    </p>

</section>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<section class="panel">

    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">

        <div>
            <h2 class="section-title">Your Products</h2>

            <p class="section-description mb-0">
                Products saved here can be reused in your daily menus.
            </p>
        </div>

        <a href="<?= site_url('products/create') ?>"
           class="btn btn-ordzee">
            + Add Product
        </a>

    </div>

    <?php if (empty($products)): ?>

        <div class="text-center py-5">

            <div class="mb-3" style="font-size:38px;">▦</div>

            <h5 class="fw-bold">No products added yet</h5>

            <p class="muted mb-0">
                Your products will appear here after you add them to your catalogue.
            </p>

        </div>

    <?php else: ?>

        <div class="table-responsive">

            <table class="table menu-table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($products as $product): ?>

                        <tr>

                            <td class="fw-semibold">
                                <?= esc($product['product_name']) ?>
                            </td>

                            <td>
                                <?php if ($product['is_active']): ?>
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    <a
                                        href="<?= site_url('products/edit/' . $product['id']) ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="<?= site_url('products/toggle-status/' . $product['id']) ?>"
                                        method="post"
                                        class="mb-0"
                                        onsubmit="return confirm('Are you sure you want to <?= $product['is_active'] ? 'deactivate' : 'activate' ?> this product?');"
                                    >
                                        <?= csrf_field() ?>

                                        <?php if ($product['is_active']): ?>

                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Deactivate
                                            </button>

                                        <?php else: ?>

                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                Activate
                                            </button>

                                        <?php endif; ?>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>

<?= $this->endSection() ?>