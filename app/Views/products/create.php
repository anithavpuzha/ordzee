<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<section class="panel">

    <div class="mb-4">
        <h2 class="section-title">Add Product</h2>

        <p class="section-description">
            Add a product to your reusable product catalogue.
        </p>
    </div>

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

    <form action="<?= site_url('products/create') ?>" method="post">

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
                value="<?= esc(old('product_name') ?? '') ?>"
                placeholder="Example: Rice"
                required
            >
        </div>

        <div class="d-flex gap-2">

            <button type="submit" class="btn btn-ordzee">
                Save Product
            </button>

            <a href="<?= site_url('products') ?>"
               class="btn btn-outline-secondary">
                Cancel
            </a>

        </div>

    </form>

</section>

<?= $this->endSection() ?>