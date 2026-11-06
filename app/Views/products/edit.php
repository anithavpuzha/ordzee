<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<section class="panel">

    <div class="mb-4">
        <h2 class="section-title">Edit Product</h2>

        <p class="section-description">
            Update the product name in your reusable catalogue.
            Existing daily menus and orders will remain unchanged.
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
        action="<?= site_url('products/update/' . $product['id']) ?>"
        method="post"
    >

        <?= csrf_field() ?>

        <div class="mb-4">

            <label for="product_name" class="form-label fw-semibold">
                Product Name
            </label>

            <input
                type="text"
                class="form-control"
                id="product_name"
                name="product_name"
                maxlength="150"
                value="<?= esc(old('product_name') ?? $product['product_name']) ?>"
                placeholder="Example: Rice"
                required
            >

        </div>

        <div class="d-flex gap-2">

            <button
                type="submit"
                class="btn btn-ordzee"
                onclick="return confirm('Are you sure you want to update this product name?');"
            >
                Update Product
            </button>

            <a
                href="<?= site_url('products') ?>"
                class="btn btn-outline-secondary"
            >
                Cancel
            </a>

        </div>

    </form>

</section>

<?= $this->endSection() ?>