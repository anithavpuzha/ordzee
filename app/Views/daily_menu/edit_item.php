<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<section class="panel">

    <div class="mb-4">
        <h2 class="section-title">Edit Menu Item</h2>

        <p class="section-description">
            Update the unit and selling price for
            <?= esc($item['product_name']) ?>.
            Changes will immediately appear on your permanent business menu. Previous customer orders will keep their original prices.
        </p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form
        action="<?= site_url('daily-menu/update-item/' . $item['id']) ?>"
        method="post"
    >
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Product Name</label>

            <input
                type="text"
                class="form-control"
                value="<?= esc($item['product_name']) ?>"
                readonly
            >

            <div class="form-text">
                Product name cannot be changed here.
            </div>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">
                Unit / Quantity
            </label>

            <input
                type="text"
                name="unit"
                id="unit"
                class="form-control"
                maxlength="50"
                value="<?= esc(old('unit') ?? $item['unit']) ?>"
                placeholder="Example: 1kg, 5kg, 1 packet"
                required
            >
        </div>

        <div class="mb-4">
            <label for="price" class="form-label">
                Selling Price (₹)
            </label>

            <input
                type="number"
                name="price"
                id="price"
                class="form-control"
                min="0"
                max="99999999.99"
                step="0.01"
                value="<?= esc(old('price') ?? $item['price']) ?>"
                required
            >
        </div>

        <div class="d-flex gap-2">

            <button
                type="submit"
                class="btn btn-ordzee"
                onclick="return confirm('Are you sure you want to update this menu item?');"
            >
                Update Menu Item
            </button>

            <a
                href="<?= site_url('daily-menu/view/' . $menu['id']) ?>"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </form>

</section>

<?= $this->endSection() ?>