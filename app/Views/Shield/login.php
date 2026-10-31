<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Sign in | Ordzee<?= $this->endSection() ?>

<?= $this->section('main') ?>

<style>
    body {
        background: #f4f7fb;
        color: #172554;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    .login-wrapper {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px 15px;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 20px;
        padding: 38px;
        box-shadow: 0 15px 45px rgba(23, 37, 84, 0.08);
    }

    .brand-mark {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #172554;
        color: #fff;
        border-radius: 15px;
        font-size: 25px;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .brand-name {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 4px;
    }

    .brand-name span {
        color: #0d9488;
    }

    .login-subtitle {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 30px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control {
        min-height: 48px;
        border: 1px solid #dbe3ef;
        border-radius: 10px;
        padding: 12px 14px;
    }

    .form-control:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
    }

    .btn-ordzee {
        min-height: 49px;
        background: #172554;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .btn-ordzee:hover {
        background: #243b73;
        color: #fff;
    }

    .login-footer {
        margin-top: 26px;
        color: #64748b;
        font-size: 12px;
        text-align: center;
    }

    .text-accent {
        color: #0d9488;
    }

    @media (max-width: 480px) {
        .login-card {
            padding: 26px 21px;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <div class="brand-mark">O</div>

        <div class="brand-name">
            ordzee<span>.</span>
        </div>

        <h1 class="h4 fw-bold mt-4 mb-2">Welcome back</h1>

        <p class="login-subtitle">
            Sign in to manage your shop and orders.
        </p>

        <?php if (session('error') !== null): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc(session('error')) ?>
            </div>
        <?php endif ?>

        <?php if (session('errors') !== null): ?>
            <div class="alert alert-danger" role="alert">
                <?php if (is_array(session('errors'))): ?>
                    <?php foreach (session('errors') as $error): ?>
                        <div><?= esc($error) ?></div>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (session('message') !== null): ?>
            <div class="alert alert-success" role="alert">
                <?= esc(session('message')) ?>
            </div>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="email" class="form-label">
                    Email address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="you@example.com"
                    value="<?= old('email') ?>"
                    autocomplete="email"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    Password
                </label>

                <div class="input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="togglePassword"
                        aria-label="Show password"
                    >
                        Show
                    </button>
                </div>
            </div>

            <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                <div class="form-check mb-4">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                        <?php if (old('remember')): ?>checked<?php endif ?>
                    >

                    <label class="form-check-label small" for="remember">
                        Remember me
                    </label>
                </div>
            <?php endif ?>

            <button type="submit" class="btn btn-ordzee w-100">
                Sign in to Ordzee
            </button>

            <?php if (setting('Auth.allowMagicLinkLogins')): ?>
                <div class="text-center mt-3">
                    <a href="<?= url_to('magic-link') ?>"
                       class="text-accent text-decoration-none small">
                        Forgot your password?
                    </a>
                </div>
            <?php endif ?>
        </form>

        <div class="login-footer">
            Secure seller access &bull; Powered by Ordzee
        </div>

    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener(
        'click',
        function () {
            const password = document.getElementById('password');
            const showing = password.type === 'text';

            password.type = showing ? 'password' : 'text';
            this.textContent = showing ? 'Show' : 'Hide';
            this.setAttribute(
                'aria-label',
                showing ? 'Show password' : 'Hide password'
            );
        }
    );
</script>

<?= $this->endSection() ?>