<?= $this->extend('layouts/customer') ?>

<?= $this->section('content') ?>

<?php

$oldQuantities = old('quantities') ?? [];

if (! is_array($oldQuantities)) {
    $oldQuantities = [];
}

$customerName = old('customer_name') ?? '';
$customerMobile = old('customer_mobile') ?? '';
$customerEmail = old('customer_email') ?? '';
$deliveryAddress = old('delivery_address') ?? '';

?>

<style>
    .public-menu-page {
        --menu-border: #e8edf3;
        --menu-muted: #718096;
        --menu-heading: #263445;
        --menu-primary: #198754;
        padding-bottom: 35px;
    }

    .public-menu-page .business-header {
        background: #fff;
        border: 1px solid var(--menu-border);
        border-radius: 16px;
        padding: 30px 20px;
        text-align: center;
        margin-bottom: 25px;
    }

    .public-menu-page .business-name {
        font-size: clamp(25px, 5vw, 34px);
        font-weight: 750;
        color: var(--menu-heading);
        overflow-wrap: anywhere;
    }

    .public-menu-page .business-address {
        color: var(--menu-muted);
        font-size: 14px;
        margin-top: 8px;
    }

    .public-menu-page .menu-description {
        color: var(--menu-muted);
        font-size: 14px;
    }

    .public-menu-page .search-box {
        position: relative;
        max-width: 500px;
        margin: 20px auto 25px;
    }

    .public-menu-page .search-box input {
        border-radius: 10px;
        padding: 12px 15px 12px 42px;
        border-color: var(--menu-border);
    }

    .public-menu-page .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--menu-muted);
    }

    .public-menu-page .product-card {
        height: 100%;
        border: 1px solid var(--menu-border);
        border-radius: 12px;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .public-menu-page .product-card.selected {
        border-color: var(--menu-primary);
        box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.08);
    }

    .public-menu-page .product-card .card-body {
        padding: 18px;
    }

    .public-menu-page .product-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--menu-heading);
        overflow-wrap: anywhere;
    }

    .public-menu-page .product-unit {
        font-size: 13px;
        color: var(--menu-muted);
        margin-top: 5px;
    }

    .public-menu-page .product-price {
        font-size: 18px;
        font-weight: 750;
        color: var(--menu-heading);
        margin-top: 12px;
    }

    .public-menu-page .quantity-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--menu-muted);
        margin-bottom: 6px;
    }

    .public-menu-page .quantity-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .public-menu-page .quantity-control .quantity-input {
        min-width: 0;
        text-align: center;
        font-weight: 650;
        padding: 8px 4px;
    }

    .public-menu-page .quantity-button {
        width: 36px;
        height: 38px;
        min-width: 36px;
        border: 1px solid var(--menu-border);
        background: #f8fafc;
        border-radius: 7px;
        color: var(--menu-heading);
        font-weight: 700;
    }

    .public-menu-page .quantity-button:hover {
        background: #e9f5ee;
        border-color: var(--menu-primary);
    }

    .public-menu-page .order-summary {
        background: #fff;
        border: 1px solid var(--menu-border);
        border-radius: 12px;
        padding: 20px;
        margin-top: 25px;
    }

    .public-menu-page .summary-heading {
        font-size: 18px;
        font-weight: 750;
        color: var(--menu-heading);
    }

    .public-menu-page .summary-empty {
        color: var(--menu-muted);
        text-align: center;
        padding: 20px 5px;
        font-size: 14px;
    }

    .public-menu-page .summary-item {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--menu-border);
        font-size: 13px;
    }

    .public-menu-page .summary-item-name {
        font-weight: 600;
        color: var(--menu-heading);
        overflow-wrap: anywhere;
    }

    .public-menu-page .summary-item-total {
        white-space: nowrap;
        font-weight: 650;
    }

    .public-menu-page .summary-total {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding-top: 15px;
        margin-top: 5px;
        font-size: 17px;
        font-weight: 750;
        color: var(--menu-heading);
    }

    .public-menu-page .customer-section {
        background: #fff;
        border: 1px solid var(--menu-border);
        border-radius: 12px;
        padding: 22px;
        margin-top: 25px;
    }

    .public-menu-page .customer-heading {
        font-size: 19px;
        font-weight: 750;
        color: var(--menu-heading);
        margin-bottom: 5px;
    }

    .public-menu-page .customer-description {
        color: var(--menu-muted);
        font-size: 13px;
        margin-bottom: 20px;
    }

    .public-menu-page .form-label {
        font-size: 13px;
        font-weight: 650;
        color: var(--menu-heading);
    }

    .public-menu-page .form-control {
        border-color: var(--menu-border);
        border-radius: 8px;
        padding: 10px 12px;
    }

    .public-menu-page .form-control:focus {
        border-color: var(--menu-primary);
        box-shadow: 0 0 0 0.15rem rgba(25, 135, 84, 0.12);
    }

    .public-menu-page .submit-order-button {
        border-radius: 9px;
        padding: 13px;
        font-weight: 700;
        font-size: 15px;
    }

    .public-menu-page .modal-content {
        border: 0;
        border-radius: 14px;
    }

    .public-menu-page .confirmation-list {
        max-height: 250px;
        overflow-y: auto;
    }

    .public-menu-page .confirmation-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid var(--menu-border);
        font-size: 13px;
    }

    .public-menu-page .confirmation-total {
        display: flex;
        justify-content: space-between;
        font-weight: 750;
        font-size: 17px;
        padding-top: 15px;
    }

    .public-menu-page .no-search-results {
        display: none;
        padding: 25px;
        text-align: center;
        color: var(--menu-muted);
    }

    @media (max-width: 576px) {
        .public-menu-page .business-header {
            padding: 23px 15px;
        }

        .public-menu-page .product-card .card-body {
            padding: 15px;
        }

        .public-menu-page .order-summary,
        .public-menu-page .customer-section {
            padding: 16px;
        }
    }
</style>

<div class="public-menu-page">

    <div class="container">

        <!-- Business Header -->

        <div class="business-header">

            <div class="business-name">
                <?= esc($business['business_name']) ?>
            </div>

            <?php if (! empty($business['address'])): ?>

                <div class="business-address">
                    <i class="bi bi-geo-alt me-1"></i>
                    <?= esc($business['address']) ?>
                </div>

            <?php endif; ?>

        </div>


        <!-- Flash Error -->

        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert alert-danger alert-dismissible fade show"
                role="alert">

                <?= esc(session()->getFlashdata('error')) ?>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                </button>

            </div>

        <?php endif; ?>


        <?php if (empty($items)): ?>

            <div class="alert alert-info text-center p-4">

                <i class="bi bi-basket fs-2 d-block mb-2"></i>

                <strong>No menu items are currently available.</strong>

                <div class="small mt-1">
                    Please check again later.
                </div>

            </div>

        <?php else: ?>


            <!-- Menu Description -->

            <div class="text-center">

                <div class="menu-description">
                    Browse the items below and choose your quantities.
                </div>

            </div>


            <!-- Main Order Form -->

            <form method="post"
                id="publicOrderForm"
                action="<?= site_url('menu/' . $business['slug'] . '/place-order') ?>">

                <?= csrf_field() ?>


                <!-- Product Search -->

                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input type="search"
                        id="productSearch"
                        class="form-control"
                        placeholder="Search items"
                        autocomplete="off"
                        aria-label="Search items">

                </div>


                <!-- Product Cards -->

                <div class="row g-3" id="productList">

                    <?php foreach ($items as $item): ?>

                        <?php

                        $oldQuantity = $oldQuantities[$item['id']] ?? '0';

                        if (! is_scalar($oldQuantity)) {
                            $oldQuantity = '0';
                        }

                        ?>

                        <div class="col-12 col-sm-6 col-lg-4 product-column"
                            data-product-name="<?= esc(strtolower($item['product_name']), 'attr') ?>">

                            <div class="card product-card">

                                <div class="card-body">

                                    <div class="product-name">
                                        <?= esc($item['product_name']) ?>
                                    </div>

                                    <div class="product-unit">
                                        <?= esc($item['unit']) ?>
                                    </div>

                                    <div class="product-price">
                                        ₹<?= number_format((float) $item['price'], 2) ?>
                                    </div>


                                    <div class="mt-3">

                                        <label class="quantity-label"
                                            for="quantity-<?= esc($item['id']) ?>">

                                            Quantity

                                        </label>

                                        <div class="quantity-control">

                                            <button type="button"
                                                class="quantity-button quantity-minus"
                                                aria-label="Decrease quantity">

                                                −

                                            </button>

                                            <input
                                                type="number"
                                                id="quantity-<?= esc($item['id']) ?>"
                                                name="quantities[<?= esc($item['id']) ?>]"
                                                class="form-control quantity-input"
                                                min="0"
                                                max="999999"
                                                step="0.01"
                                                value="<?= esc($oldQuantity, 'attr') ?>"
                                                data-price="<?= esc(number_format((float) $item['price'], 2, '.', ''), 'attr') ?>"
                                                data-name="<?= esc($item['product_name'], 'attr') ?>"
                                                data-unit="<?= esc($item['unit'], 'attr') ?>"
                                                inputmode="decimal"
                                                aria-label="Quantity for <?= esc($item['product_name'], 'attr') ?>"
                                            >

                                            <button type="button"
                                                class="quantity-button quantity-plus"
                                                aria-label="Increase quantity">

                                                +

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>


                    <div class="col-12 no-search-results"
                        id="noSearchResults">

                        <i class="bi bi-search fs-3 d-block mb-2"></i>

                        No matching menu items found.

                    </div>

                </div>


                <!-- Live Order Summary -->

                <div class="order-summary">

                    <div class="d-flex justify-content-between
                        align-items-center flex-wrap gap-2">

                        <div class="summary-heading">
                            <i class="bi bi-basket2 me-1"></i>
                            Your Order
                        </div>

                        <span class="badge text-bg-success"
                            id="selectedItemCount">

                            0 items

                        </span>

                    </div>


                    <div id="summaryItems">

                        <div class="summary-empty">

                            <i class="bi bi-cart3 fs-3 d-block mb-2"></i>

                            No items selected yet.

                            <div class="small mt-1">
                                Choose quantities from the menu above.
                            </div>

                        </div>

                    </div>


                    <div class="summary-total">

                        <span>Estimated Subtotal</span>

                        <span id="summarySubtotal">
                            ₹0.00
                        </span>

                    </div>

                    <div class="small text-muted mt-2">
                        Final charges, if any, will be confirmed by the business.
                    </div>

                </div>


                <!-- Customer Details -->

                <div class="customer-section">

                    <div class="customer-heading">
                        <i class="bi bi-person-circle me-1"></i>
                        Your Details
                    </div>

                    <div class="customer-description">
                        Please provide your contact and delivery information.
                    </div>


                    <div class="row g-3">

                        <!-- Customer Name -->

                        <div class="col-md-6">

                            <label class="form-label"
                                for="customerName">

                                Customer Name *

                            </label>

                            <input
                                type="text"
                                id="customerName"
                                name="customer_name"
                                class="form-control"
                                maxlength="150"
                                value="<?= esc($customerName, 'attr') ?>"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required
                            >

                        </div>


                        <!-- Mobile Number -->

                        <div class="col-md-6">

                            <label class="form-label"
                                for="customerMobile">

                                Mobile Number *

                            </label>

                            <input
                                type="tel"
                                id="customerMobile"
                                name="customer_mobile"
                                class="form-control"
                                maxlength="20"
                                value="<?= esc($customerMobile, 'attr') ?>"
                                placeholder="Enter your mobile number"
                                autocomplete="tel"
                                required
                            >

                        </div>


                        <!-- Email -->

                        <div class="col-md-6">

                            <label class="form-label"
                                for="customerEmail">

                                Email Address
                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>

                            </label>

                            <input
                                type="email"
                                id="customerEmail"
                                name="customer_email"
                                class="form-control"
                                maxlength="150"
                                value="<?= esc($customerEmail, 'attr') ?>"
                                placeholder="Enter your email address"
                                autocomplete="email"
                            >

                        </div>


                        <!-- Delivery Address -->

                        <div class="col-md-6">

                            <label class="form-label"
                                for="deliveryAddress">

                                Delivery Address *

                            </label>

                            <textarea
                                id="deliveryAddress"
                                name="delivery_address"
                                class="form-control"
                                rows="3"
                                maxlength="2000"
                                placeholder="Enter your complete delivery address"
                                autocomplete="street-address"
                                required><?= esc($deliveryAddress) ?></textarea>

                        </div>

                    </div>


                    <!-- Submit -->

                    <button type="submit"
                        class="btn btn-success w-100 submit-order-button mt-4">

                        <i class="bi bi-whatsapp me-2"></i>

                        Review Order & Continue to WhatsApp

                    </button>

                    <div class="text-center text-muted small mt-3">
                        WhatsApp will open with your selected items.
                    </div>

                </div>

            </form>


            <!-- Order Confirmation Modal -->

            <div class="modal fade"
                id="orderConfirmationModal"
                tabindex="-1"
                aria-labelledby="orderConfirmationModalLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title fw-bold"
                                id="orderConfirmationModalLabel">

                                Review Your Order

                            </h5>

                            <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                            </button>

                        </div>


                        <div class="modal-body">

                            <p class="text-muted small">
                                Please check your selected items before continuing.
                            </p>


                            <div class="confirmation-list"
                                id="confirmationItems">
                            </div>


                            <div class="confirmation-total">

                                <span>Estimated Subtotal</span>

                                <span id="confirmationSubtotal">
                                    ₹0.00
                                </span>

                            </div>


                            <div class="alert alert-light border small mt-3 mb-0">

                                <i class="bi bi-info-circle me-1"></i>

                                WhatsApp will open with your order details.
                                You must press Send in WhatsApp to send your order.

                            </div>

                        </div>


                        <div class="modal-footer">

                            <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Go Back

                            </button>


                            <button type="button"
                                class="btn btn-success"
                                id="confirmOrderButton">

                                <i class="bi bi-whatsapp me-1"></i>

                                Confirm & Continue

                            </button>

                        </div>

                    </div>

                </div>

            </div>


        <?php endif; ?>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const orderForm = document.getElementById('publicOrderForm');

    if (!orderForm) {
        return;
    }

    const quantityInputs = document.querySelectorAll('.quantity-input');

    const summaryItems = document.getElementById('summaryItems');

    const summarySubtotal = document.getElementById('summarySubtotal');

    const selectedItemCount = document.getElementById('selectedItemCount');

    const searchInput = document.getElementById('productSearch');

    const noSearchResults = document.getElementById('noSearchResults');

    const confirmationItems = document.getElementById('confirmationItems');

    const confirmationSubtotal = document.getElementById('confirmationSubtotal');

    const confirmOrderButton = document.getElementById('confirmOrderButton');

    const modalElement = document.getElementById('orderConfirmationModal');

    let confirmationInProgress = false;


    // Format currency.

    function formatCurrency(amount) {

        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);

    }


    // Format quantity.

    function formatQuantity(quantity) {

        return Number(quantity).toLocaleString('en-IN', {
            maximumFractionDigits: 2
        });

    }


    // Read selected items and calculate subtotal.

    function getSelectedItems() {

        const selectedItems = [];

        let subtotal = 0;

        quantityInputs.forEach(function (input) {

            const quantity = parseFloat(input.value) || 0;

            const price = parseFloat(input.dataset.price) || 0;

            if (quantity > 0) {

                const itemTotal = Math.round(quantity * price * 100) / 100;

                selectedItems.push({

                    name: input.dataset.name,

                    unit: input.dataset.unit,

                    quantity: quantity,

                    price: price,

                    total: itemTotal

                });

                subtotal += itemTotal;

            }

        });

        return {
            items: selectedItems,
            subtotal: Math.round(subtotal * 100) / 100
        };

    }


    // Refresh the live order summary.

    function updateOrderSummary() {

        const result = getSelectedItems();

        summaryItems.innerHTML = '';

        selectedItemCount.textContent =
            result.items.length + (result.items.length === 1 ? ' item' : ' items');


        if (result.items.length === 0) {

            summaryItems.innerHTML = `
                <div class="summary-empty">
                    <i class="bi bi-cart3 fs-3 d-block mb-2"></i>
                    No items selected yet.
                    <div class="small mt-1">
                        Choose quantities from the menu above.
                    </div>
                </div>
            `;

        } else {

            result.items.forEach(function (item) {

                const row = document.createElement('div');

                row.className = 'summary-item';

                const name = document.createElement('div');

                name.className = 'summary-item-name';

                name.textContent =
                    item.name + ' (' + item.unit + ') × ' +
                    formatQuantity(item.quantity);


                const total = document.createElement('div');

                total.className = 'summary-item-total';

                total.textContent = formatCurrency(item.total);


                row.appendChild(name);

                row.appendChild(total);

                summaryItems.appendChild(row);

            });

        }


        summarySubtotal.textContent = formatCurrency(result.subtotal);


        // Highlight selected product cards.

        quantityInputs.forEach(function (input) {

            const card = input.closest('.product-card');

            if (card) {

                card.classList.toggle(
                    'selected',
                    (parseFloat(input.value) || 0) > 0
                );

            }

        });

    }


    // Quantity input changes.

    quantityInputs.forEach(function (input) {

        input.addEventListener('input', updateOrderSummary);

        input.addEventListener('change', function () {

            let quantity = parseFloat(input.value);

            if (!Number.isFinite(quantity) || quantity < 0) {
                quantity = 0;
            }

            if (quantity > 999999) {
                quantity = 999999;
            }

            quantity = Math.round(quantity * 100) / 100;

            input.value = quantity;

            updateOrderSummary();

        });

    });


    // Increase quantity buttons.

    document.querySelectorAll('.quantity-plus').forEach(function (button) {

        button.addEventListener('click', function () {

            const input = button
                .closest('.quantity-control')
                .querySelector('.quantity-input');

            let quantity = parseFloat(input.value) || 0;

            if (quantity < 999999) {

                input.value = Math.round((quantity + 1) * 100) / 100;

            }

            updateOrderSummary();

        });

    });


    // Decrease quantity buttons.

    document.querySelectorAll('.quantity-minus').forEach(function (button) {

        button.addEventListener('click', function () {

            const input = button
                .closest('.quantity-control')
                .querySelector('.quantity-input');

            let quantity = parseFloat(input.value) || 0;

            quantity = Math.max(0, quantity - 1);

            input.value = Math.round(quantity * 100) / 100;

            updateOrderSummary();

        });

    });


    // Product search.

    searchInput.addEventListener('input', function () {

        const searchTerm = searchInput.value.trim().toLowerCase();

        const productColumns = document.querySelectorAll('.product-column');

        let visibleCount = 0;

        productColumns.forEach(function (column) {

            const productName = column.dataset.productName || '';

            const matches = productName.includes(searchTerm);

            column.style.display = matches ? '' : 'none';

            if (matches) {
                visibleCount++;
            }

        });

        noSearchResults.style.display =
            visibleCount === 0 ? 'block' : 'none';

    });


    // Populate the order confirmation modal.

    function populateConfirmation() {

        const result = getSelectedItems();

        confirmationItems.innerHTML = '';

        result.items.forEach(function (item) {

            const row = document.createElement('div');

            row.className = 'confirmation-item';


            const name = document.createElement('div');

            name.textContent =
                item.name + ' (' + item.unit + ') × ' +
                formatQuantity(item.quantity);


            const total = document.createElement('strong');

            total.textContent = formatCurrency(item.total);


            row.appendChild(name);

            row.appendChild(total);

            confirmationItems.appendChild(row);

        });

        confirmationSubtotal.textContent =
            formatCurrency(result.subtotal);

    }


    // Validate customer details and selected items.

    function validateOrder() {

        const result = getSelectedItems();

        if (result.items.length === 0) {

            alert('Please select at least one menu item before continuing.');

            const firstQuantityInput = quantityInputs[0];

            if (firstQuantityInput) {
                firstQuantityInput.focus();
            }

            return false;

        }


        // Check required customer fields.

        const customerName = document.getElementById('customerName');

        const customerMobile = document.getElementById('customerMobile');

        const deliveryAddress = document.getElementById('deliveryAddress');


        if (!customerName.value.trim()) {

            alert('Please enter your name.');

            customerName.focus();

            return false;

        }


        if (!customerMobile.value.trim()) {

            alert('Please enter your mobile number.');

            customerMobile.focus();

            return false;

        }


        if (!deliveryAddress.value.trim()) {

            alert('Please enter your delivery address.');

            deliveryAddress.focus();

            return false;

        }


        return true;

    }


    // Show confirmation before submitting.

    orderForm.addEventListener('submit', function (event) {

        if (confirmationInProgress) {
            return;
        }


        event.preventDefault();


        // Validate selected items and customer details.

        if (!validateOrder()) {
            return;
        }


        // Browser's built-in validation for all form fields.

        if (!orderForm.checkValidity()) {

            orderForm.reportValidity();

            return;

        }


        populateConfirmation();


        // Use Bootstrap modal when available.

        if (window.bootstrap && window.bootstrap.Modal) {

            const modal = window.bootstrap.Modal.getOrCreateInstance(
                modalElement
            );

            modal.show();

        } else {

            // Fallback if Bootstrap JavaScript is unavailable.

            if (window.confirm('Continue to WhatsApp with your order?')) {

                confirmationInProgress = true;

                orderForm.requestSubmit();

            }

        }

    });


    // Confirm order and submit the form.

    confirmOrderButton.addEventListener('click', function () {

        // Validate again before final submission.

        if (!validateOrder()) {
            return;
        }

        if (!orderForm.checkValidity()) {

            orderForm.reportValidity();

            return;

        }


        confirmationInProgress = true;

        confirmOrderButton.disabled = true;

        confirmOrderButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-1"></span> Continuing...';

        orderForm.requestSubmit();

    });


    // Initial summary.
    updateOrderSummary();

});
</script>

<?= $this->endSection() ?>