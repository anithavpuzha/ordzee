<?= $this->extend('layouts/seller') ?>

<?= $this->section('content') ?>

<section class="panel mb-4">

    <h2 class="section-title">
        <?= esc($business['business_name']) ?>
    </h2>

    <p class="section-description mb-0">
        Manage your permanent business menu here.
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

    <div class="d-flex justify-content-between align-items-center gap-3">

        <div>
            <h2 class="section-title">Your Menu</h2>

            <p class="section-description mb-0">
                Your menu is permanent and can be updated whenever needed.
            </p>
        </div>

        <a href="<?= site_url('daily-menu') ?>"
           class="btn btn-ordzee">
            Manage Menu
        </a>

    </div>

</section>

<?= $this->endSection() ?>