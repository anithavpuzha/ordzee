<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<section class="panel">

    <div class="mb-4">
        <h2 class="section-title">Create Daily Menu</h2>

        <p class="section-description">
            Choose a date and give your menu a title.
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

    <form action="<?= site_url('daily-menu/create') ?>" method="post">

        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="menu_date" class="form-label fw-semibold">
                Menu Date
            </label>

            <input
                type="date"
                class="form-control"
                id="menu_date"
                name="menu_date"
                value="<?= esc(old('menu_date') ?? date('Y-m-d')) ?>"
                required
            >
        </div>

        <div class="mb-4">
            <label for="menu_title" class="form-label fw-semibold">
                Menu Title
            </label>

            <input
                type="text"
                class="form-control"
                id="menu_title"
                name="menu_title"
                maxlength="150"
                value="<?= esc(old('menu_title') ?? 'Daily Menu') ?>"
                placeholder="Example: Today's Special Menu"
                required
            >
        </div>

        <div class="d-flex gap-2">

            <button type="submit" class="btn btn-ordzee">
                Save Menu
            </button>

            <a href="<?= site_url('daily-menu') ?>"
               class="btn btn-outline-secondary">
                Cancel
            </a>

        </div>

    </form>

</section>

<?= $this->endSection() ?>