<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Dashboard | Ordzee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            Ordzee
        </a>

        <a href="<?= url_to('logout') ?>" class="btn btn-light btn-sm">
            Sign Out
        </a>
    </div>
</nav>

<div class="container py-4">

    <div class="mb-4">
        <h2>Business Dashboard</h2>
        <p class="text-muted">
            Manage your business, daily menus and customer orders.
        </p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4><?= esc($business['business_name']) ?></h4>

            <p class="text-muted mb-2">
                <?= esc($business['business_type'] ?? 'Business') ?>
            </p>

            <p class="mb-1">
                <strong>Public Menu Link:</strong>
            </p>

            <a href="<?= site_url($business['slug']) ?>" target="_blank">
                <?= esc(site_url($business['slug'])) ?>
            </a>

            <hr>

            <p class="mb-1">
                <strong>WhatsApp Number:</strong>
                <?= esc($business['whatsapp_number']) ?>
            </p>

            <p class="mb-0">
                <strong>Address:</strong>
                <?= esc($business['address'] ?? 'Not added') ?>
            </p>
        </div>
    </div>

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Daily Menu</h5>
                    <p class="text-muted">
                        Create and manage your daily menu.
                    </p>
                    <button class="btn btn-primary" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Orders</h5>
                    <p class="text-muted">
                        View and manage customer orders.
                    </p>
                    <button class="btn btn-primary" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5>Payments</h5>
                    <p class="text-muted">
                        Track payments and pending balances.
                    </p>
                    <button class="btn btn-primary" disabled>
                        Coming Soon
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>