<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Business | Ordzee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h2 class="text-center mb-2">Register Your Business</h2>

                    <p class="text-center text-muted mb-4">
                        Create your business profile to start managing menus and orders.
                    </p>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('business/register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="business_name" class="form-label">
                                Business Name *
                            </label>

                            <input
                                type="text"
                                name="business_name"
                                id="business_name"
                                class="form-control"
                                value="<?= old('business_name') ?>"
                                placeholder="e.g. Annapurna Tiffin Centre"
                                maxlength="150"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="business_type" class="form-label">
                                Business Type
                            </label>

                            <input
                                type="text"
                                name="business_type"
                                id="business_type"
                                class="form-control"
                                value="<?= old('business_type') ?>"
                                placeholder="e.g. Tiffin Centre, Bakery, Grocery"
                                maxlength="80"
                            >
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">
                                Public Link Name *
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">ordzee.in/</span>

                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    class="form-control"
                                    value="<?= old('slug') ?>"
                                    placeholder="annapurna-tiffin"
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div class="form-text">
                                Use lowercase letters, numbers and hyphens.
                                If this link is already taken, choose another.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="whatsapp_number" class="form-label">
                                WhatsApp Number *
                            </label>

                            <input
                                type="tel"
                                name="whatsapp_number"
                                id="whatsapp_number"
                                class="form-control"
                                value="<?= old('whatsapp_number') ?>"
                                placeholder="e.g. 919876543210"
                                maxlength="20"
                                required
                            >

                            <div class="form-text">
                                Include the country code, such as 91 for India.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                Business Address
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                class="form-control"
                                rows="3"
                                maxlength="500"
                                placeholder="Enter your business address"
                            ><?= old('address') ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Register Business
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>