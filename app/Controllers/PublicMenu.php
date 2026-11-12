<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\DailyMenuModel;
use App\Models\DailyMenuItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\OrderStatusHistoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PublicMenu extends BaseController
{
    /**
     * Display the public menu.
     */
    public function show($slug)
    {
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('slug', $slug)
            ->first();

        if (! $business || ! $business['is_active']) {
            throw PageNotFoundException::forPageNotFound(
                'Business menu not found.'
            );
        }

        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            throw PageNotFoundException::forPageNotFound(
                'Menu not found.'
            );
        }

        // Display only currently available items.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $items = $dailyMenuItemModel
            ->where('daily_menu_id', $menu['id'])
            ->where('is_available', 1)
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('public_menu/show', [
            'pageTitle' => $business['business_name'] . ' Menu',
            'business'  => $business,
            'menu'      => $menu,
            'items'     => $items,
        ]);
    }

    /**
     * Validate and place a customer order.
     */
    public function placeOrder($slug)
    {
        // Find the active business.
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('slug', $slug)
            ->first();

        if (! $business || ! $business['is_active']) {
            throw PageNotFoundException::forPageNotFound(
                'Business menu not found.'
            );
        }

        // Get customer details.
        $customerName = trim(
            (string) $this->request->getPost('customer_name')
        );

        $customerMobile = trim(
            (string) $this->request->getPost('customer_mobile')
        );

        $customerEmail = trim(
            (string) $this->request->getPost('customer_email')
        );

        $deliveryAddress = trim(
            (string) $this->request->getPost('delivery_address')
        );

        // Validate customer details.
        $validation = service('validation');

        $validation->setRules([

            'customer_name' => [
                'rules' => 'required|max_length[150]',
                'errors' => [
                    'required' => 'Please enter your name.',
                ],
            ],

            'customer_mobile' => [
                'rules' => 'required|max_length[20]|regex_match[/^[0-9+\-\s()]{7,20}$/]',
                'errors' => [
                    'required' => 'Please enter your mobile number.',
                    'regex_match' => 'Please enter a valid mobile number.',
                ],
            ],

            'customer_email' => [
                'rules' => 'permit_empty|valid_email|max_length[150]',
                'errors' => [
                    'valid_email' => 'Please enter a valid email address.',
                ],
            ],

            'delivery_address' => [
                'rules' => 'required|max_length[2000]',
                'errors' => [
                    'required' => 'Please enter your delivery address.',
                ],
            ],

        ]);

        if (! $validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    implode(' ', $validation->getErrors())
                );
        }

        // Get submitted quantities.
        $quantities = $this->request->getPost('quantities');

        if (! is_array($quantities) || empty($quantities)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Please select at least one menu item.');
        }

        // Find the permanent menu.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            throw PageNotFoundException::forPageNotFound(
                'Menu not found.'
            );
        }

        // Get currently available menu items.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $availableItems = $dailyMenuItemModel
            ->where('daily_menu_id', $menu['id'])
            ->where('is_available', 1)
            ->findAll();

        // Create an array for quick item lookup.
        $availableItemsById = [];

        foreach ($availableItems as $item) {
            $availableItemsById[$item['id']] = $item;
        }

        // Prepare order items using database prices.
        $orderItems = [];

        $subtotal = 0;

        foreach ($quantities as $itemId => $quantity) {

            // Validate item ID.
            if (
                ! ctype_digit((string) $itemId) ||
                ! isset($availableItemsById[$itemId])
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'One of the selected items is no longer available. Please refresh the menu.'
                    );
            }

            // Validate quantity.
            if (! is_scalar($quantity)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid item quantity.');
            }

            $quantity = trim((string) $quantity);

            // Allow whole numbers and up to two decimal places.
            if (! preg_match('/^\d+(?:\.\d{1,2})?$/', $quantity)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please enter a valid quantity.');
            }

            $quantity = (float) $quantity;

            if (
                ! is_finite($quantity) ||
                $quantity < 0 ||
                $quantity > 999999
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please enter a valid quantity.');
            }

            // Zero means the customer does not want this item.
            if ($quantity == 0) {
                continue;
            }

            $menuItem = $availableItemsById[$itemId];

            // Always use the latest price saved in the database.
            $unitPrice = (float) $menuItem['price'];

            if (! is_finite($unitPrice) || $unitPrice < 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'An item has an invalid price. Please contact the business.');
            }

            $itemTotal = round($quantity * $unitPrice, 2);

            $subtotal += $itemTotal;

            // Store an independent snapshot for the order.
            $orderItems[] = [
                'product_id'   => $menuItem['product_id'],
                'product_name' => $menuItem['product_name'],
                'unit'         => $menuItem['unit'],
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'item_total'   => $itemTotal,
            ];
        }

        if (empty($orderItems)) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Please select at least one item with a quantity greater than zero.'
                );
        }

        $subtotal = round($subtotal, 2);

        // Initial order totals.
        $discount = 0;
        $deliveryCharge = 0;
        $grandTotal = $subtotal;

        // Prepare a unique order number.
        $orderNumber = 'ORD-' . date('YmdHis') . '-'
            . strtoupper(bin2hex(random_bytes(3)));

        // Prepare the WhatsApp message.
        // IMPORTANT: No prices or totals are included.
        $message = "Hello! I would like to place an order from "
            . $business['business_name']
            . ".\n\nMy order:\n";

        foreach ($orderItems as $item) {

            $message .= '- '
                . $item['product_name']
                . ' (' . $item['unit'] . ')'
                . ' x ' . $item['quantity']
                . "\n";
        }

        // Clean the business WhatsApp number.
        $whatsappNumber = preg_replace(
            '/\D/',
            '',
            (string) $business['whatsapp_number']
        );

        // Add India's country code if a 10-digit number was entered.
        if (strlen($whatsappNumber) === 10) {
            $whatsappNumber = '91' . $whatsappNumber;
        } elseif (
            strlen($whatsappNumber) === 11 &&
            $whatsappNumber[0] === '0'
        ) {
            $whatsappNumber = '91' . substr($whatsappNumber, 1);
        }

        if (
            strlen($whatsappNumber) < 8 ||
            strlen($whatsappNumber) > 15
        ) {
            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'The business WhatsApp number is not configured correctly.'
                );
        }

        // Start the database transaction.
        $db = \Config\Database::connect();

        try {

            $db->transBegin();

            // Save the main order.
            $orderModel = new OrderModel();

            $orderId = $orderModel->insert([

                'business_id'         => $business['id'],
                'customer_id'         => null,
                'order_number'        => $orderNumber,

                'customer_name'       => $customerName,
                'customer_mobile'     => $customerMobile,
                'customer_email'      => $customerEmail ?: null,
                'delivery_address'    => $deliveryAddress,

                'order_date'          => date('Y-m-d H:i:s'),
                'delivery_date'       => null,

                'subtotal'            => $subtotal,
                'discount'            => $discount,
                'delivery_charge'     => $deliveryCharge,
                'grand_total'         => $grandTotal,

                'amount_paid'         => 0,
                'balance_amount'      => $grandTotal,

                'order_status'        => 'pending',
                'payment_status'      => 'pending',

                'cancellation_reason' => null,
                'seller_notes'        => null,

            ], true);

            if (! $orderId) {
                throw new \RuntimeException(
                    'Unable to save the order.'
                );
            }

            // Save each item with its original name, unit and price.
            $orderItemModel = new OrderItemModel();

            foreach ($orderItems as $item) {

                $item['order_id'] = $orderId;

                if (! $orderItemModel->insert($item)) {
                    throw new \RuntimeException(
                        'Unable to save an order item.'
                    );
                }
            }

            // Save the initial order status history.
            $historyModel = new OrderStatusHistoryModel();

            if (! $historyModel->insert([

                'order_id'   => $orderId,
                'old_status' => null,
                'new_status' => 'pending',
                'remarks'    => 'Customer placed the order.',
                'created_at' => date('Y-m-d H:i:s'),

            ])) {
                throw new \RuntimeException(
                    'Unable to save order status history.'
                );
            }

            // Check transaction status.
            if ($db->transStatus() === false) {
                throw new \RuntimeException(
                    'Unable to complete the order.'
                );
            }

            $db->transCommit();

        } catch (\Throwable $e) {

            $db->transRollback();

            log_message(
                'error',
                'Customer order error: ' . $e->getMessage()
            );

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'We could not place your order. Please try again.'
                );
        }

        // Open WhatsApp with the prepared message.
        // The customer must manually press Send.
        $whatsappUrl = 'https://wa.me/'
            . $whatsappNumber
            . '?text='
            . rawurlencode($message);

        return redirect()->to($whatsappUrl);
    }
}