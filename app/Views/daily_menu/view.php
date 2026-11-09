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
                Your permanent business menu. Changes are reflected immediately on your public menu.
            </p>

            <span class="badge bg-secondary">
                <?= esc(ucfirst($menu['status'])) ?>
            </span>
        </div>

        <a href="<?= site_url('dashboard') ?>"
            class="btn btn-outline-secondary btn-sm">
            Back to Dashboard
        </a>

    </div>

</section>

<!-- Add product to menu -->
<section class="panel mb-4">

    <div class="mb-4">
        <h2 class="section-title">Add Product to Menu</h2>

        <p class="section-description mb-0">
            Select a catalogue product and enter its daily unit and selling price.
        </p>
    </div>

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

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>

        <p class="muted mb-0">
            No active products found in your catalogue.
            <a href="<?= site_url('products/create') ?>">Add a product</a>
            to your catalogue first.
        </p>

    <?php else: ?>

        <form
            action="<?= site_url('daily-menu/add-item/' . $menu['id']) ?>"
            method="post"
        >

            <?= csrf_field() ?>

            <div class="mb-3">

                <label for="product_id" class="form-label fw-semibold">
                    Select Product
                </label>

                <select
                    class="form-select"
                    id="product_id"
                    name="product_id"
                    required
                >
                    <option value="">Choose a product</option>

                    <?php foreach ($products as $product): ?>

                        <option
                            value="<?= esc($product['id']) ?>"
                            <?= old('product_id') == $product['id'] ? 'selected' : '' ?>
                        >
                            <?= esc($product['product_name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-3">

                <label for="unit" class="form-label fw-semibold">
                    Unit / Quantity
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="unit"
                    name="unit"
                    maxlength="50"
                    value="<?= esc(old('unit') ?? '') ?>"
                    placeholder="Example: 1 kg, 5 kg, 1 packet"
                    required
                >

            </div>

            <div class="mb-4">

                <label for="price" class="form-label fw-semibold">
                    Selling Price (₹)
                </label>

                <input
                    type="number"
                    class="form-control"
                    id="price"
                    name="price"
                    min="0"
                    max="99999999.99"
                    step="0.01"
                    value="<?= esc(old('price') ?? '') ?>"
                    placeholder="Example: 70"
                    required
                >

            </div>

            <button type="submit" class="btn btn-ordzee">
                Add to Menu
            </button>

        </form>

    <?php endif; ?>

</section>

<!-- Menu items -->
<section class="panel">

    <div class="mb-3">
        <h2 class="section-title">Menu Items</h2>

        <p class="section-description mb-0">
            Your menu items will appear here.
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
                        <th>Action</th>
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

                            <td>
                                <div class="d-flex align-items-center gap-2">

                                    <a
                                        href="<?= site_url('daily-menu/edit-item/' . $item['id']) ?>"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="<?= site_url('daily-menu/toggle-item-availability/' . $item['id']) ?>"
                                        method="post"
                                        class="mb-0"
                                        onsubmit="return confirm('Are you sure you want to <?= $item['is_available'] ? 'deactivate' : 'activate' ?> this menu item?');"
                                    >
                                        <?= csrf_field() ?>

                                        <?php if ($item['is_available']): ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Deactivate
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                Activate
                                            </button>
                                        <?php endif; ?>

                                    </form>

                                    <form
                                        action="<?= site_url('daily-menu/delete-item/' . $item['id']) ?>"
                                        method="post"
                                        class="mb-0"
                                        onsubmit="return confirm('Are you sure you want to delete this menu item? This action cannot be undone.');"
                                    >
                                        <?= csrf_field() ?>

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
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