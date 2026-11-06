<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<!-- Business information -->
<section class="panel mb-4">

    <h2 class="section-title">
        <?= esc($business['business_name']) ?>
    </h2>

    <p class="section-description mb-0">
        Manage menus for your business here.
    </p>

</section>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>


<!-- Saved menus -->
<section class="panel">

    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">

        <div>
            <h2 class="section-title">Your Daily Menus</h2>

            <p class="section-description mb-0">
                Your saved menus will appear here.
            </p>
        </div>

        <a href="<?= site_url('daily-menu/create') ?>"
        class="btn btn-ordzee">
            + Create Daily Menu
        </a>

    </div>

    <?php if (empty($menus)): ?>

        <div class="text-center py-5">

            <div class="mb-3" style="font-size:38px;">▦</div>

            <h5 class="fw-bold">No menus created yet</h5>

            <p class="muted mb-0">
                Your daily menus will appear here after you create them.
            </p>

        </div>

    <?php else: ?>

        <div class="table-responsive">

            <table class="table menu-table table-hover align-middle">

                <thead>
                    <tr>
                        <th>Menu Date</th>
                        <th>Menu Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($menus as $menu): ?>

                        <tr>
                            <td><?= esc($menu['menu_date']) ?></td>

                            <td><?= esc($menu['menu_title']) ?></td>

                            <td>
                                <?= esc(ucfirst($menu['status'])) ?>
                            </td>

                            <td>
                                <a href="<?= site_url('daily-menu/view/' . $menu['id']) ?>"
                                class="btn btn-sm btn-outline-primary">
                                    View Menu
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>

<?= $this->endSection() ?>