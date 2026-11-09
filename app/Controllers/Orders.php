<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\OrderStatusHistoryModel;

class Orders extends BaseController
{
    public function index()
    {
        $userId = auth()->id();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $userId)
            ->first();

        if (! $business) {
            return redirect()->to(site_url('business/register'));
        }

        $orderModel = new OrderModel();

        $orders = $orderModel
            ->where('business_id', $business['id'])
            ->orderBy('order_date', 'DESC')
            ->findAll();

        return view('orders/index', [
            'pageTitle' => 'Orders',
            'business'  => $business,
            'orders'    => $orders,
        ]);
    }

    public function view($id)
    {
        $userId = auth()->id();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $userId)
            ->first();

        if (! $business) {
            return redirect()->to(site_url('business/register'));
        }

        $orderModel = new OrderModel();

        $order = $orderModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Order not found.'
            );
        }

        $orderItemModel = new OrderItemModel();

        $items = $orderItemModel
            ->where('order_id', $order['id'])
            ->findAll();

        return view('orders/view', [
            'pageTitle' => 'Order Details',
            'business'  => $business,
            'order'     => $order,
            'items'     => $items,
        ]);
    }

    public function accept($id)
    {
        $userId = auth()->id();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $userId)
            ->first();

        if (! $business) {
            return redirect()->to(site_url('business/register'));
        }

        $orderModel = new OrderModel();

        $order = $orderModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Order not found.'
            );
        }

        if ($order['order_status'] !== 'pending') {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Only pending orders can be accepted.');
        }

        $customerMessage = trim((string) $this->request->getPost('customer_message'));

        if ($customerMessage === '' || mb_strlen($customerMessage) > 2000) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Please enter a message of up to 2000 characters.');
        }

        // Prepare the customer's WhatsApp number.
        $customerMobile = preg_replace('/\D/', '', $order['customer_mobile']);

        // Add India's country code if a 10-digit mobile number was entered.
        if (strlen($customerMobile) === 10) {
            $customerMobile = '91' . $customerMobile;
        } elseif (strlen($customerMobile) === 11 && $customerMobile[0] === '0') {
            $customerMobile = '91' . substr($customerMobile, 1);
        }

        if (strlen($customerMobile) < 8 || strlen($customerMobile) > 15) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'The customer mobile number is not valid for WhatsApp.');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $orderModel->update($id, [
                'order_status'     => 'accepted',
                'customer_message' => $customerMessage,
            ]);

            $historyModel = new OrderStatusHistoryModel();

            $historyModel->insert([
                'order_id'   => $id,
                'old_status' => 'pending',
                'new_status' => 'accepted',
                'remarks'    => $customerMessage,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Could not update order status.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Order acceptance failed: ' . $e->getMessage());

            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Could not accept the order. Please try again.');
        }

        $whatsappMessage =
            "Hello " . $order['customer_name'] . ",\n\n" .
            "Your order " . $order['order_number'] . " has been accepted.\n\n" .
            $customerMessage . "\n\n" .
            "Thank you for ordering with us!";

        $whatsappUrl = 'https://wa.me/' . $customerMobile .
            '?text=' . rawurlencode($whatsappMessage);

        return redirect()->to($whatsappUrl);
    }


    public function reject($id)
    {
        $userId = auth()->id();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $userId)
            ->first();

        if (! $business) {
            return redirect()->to(site_url('business/register'));
        }

        $orderModel = new OrderModel();

        $order = $orderModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Order not found.'
            );
        }

        if ($order['order_status'] !== 'pending') {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Only pending orders can be rejected.');
        }

        $customerMessage = trim((string) $this->request->getPost('customer_message'));

        if ($customerMessage === '' || mb_strlen($customerMessage) > 2000) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Please enter a rejection reason or message of up to 2000 characters.');
        }

        $customerMobile = preg_replace('/\D/', '', $order['customer_mobile']);

        if (strlen($customerMobile) === 10) {
            $customerMobile = '91' . $customerMobile;
        } elseif (strlen($customerMobile) === 11 && $customerMobile[0] === '0') {
            $customerMobile = '91' . substr($customerMobile, 1);
        }

        if (strlen($customerMobile) < 8 || strlen($customerMobile) > 15) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'The customer mobile number is not valid for WhatsApp.');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $orderModel->update($id, [
                'order_status'     => 'rejected',
                'customer_message' => $customerMessage,
            ]);

            $historyModel = new OrderStatusHistoryModel();

            $historyModel->insert([
                'order_id'   => $id,
                'old_status' => 'pending',
                'new_status' => 'rejected',
                'remarks'    => $customerMessage,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Could not update order status.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Order rejection failed: ' . $e->getMessage());

            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Could not reject the order. Please try again.');
        }

        $whatsappMessage =
            "Hello " . $order['customer_name'] . ",\n\n" .
            "We are sorry, but your order " . $order['order_number'] . " has been rejected.\n\n" .
            $customerMessage . "\n\n" .
            "Thank you for understanding.";

        $whatsappUrl = 'https://wa.me/' . $customerMobile .
            '?text=' . rawurlencode($whatsappMessage);

        return redirect()->to($whatsappUrl);
    }

    public function deliver($id)
    {
        $userId = auth()->id();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $userId)
            ->first();

        if (! $business) {
            return redirect()->to(site_url('business/register'));
        }

        $orderModel = new OrderModel();

        $order = $orderModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $order) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Order not found.'
            );
        }

        if (! in_array($order['order_status'], ['accepted', 'preparing', 'ready'], true)) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Only accepted, preparing, or ready orders can be marked as delivered.');
        }

        $db = \Config\Database::connect();

        $db->transBegin();

        try {
            $orderModel->update($id, [
                'order_status' => 'delivered',
            ]);

            $historyModel = new OrderStatusHistoryModel();

            $historyModel->insert([
                'order_id'   => $id,
                'old_status' => $order['order_status'],
                'new_status' => 'delivered',
                'remarks'    => 'Order marked as delivered.',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Could not update order status.');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();

            log_message('error', 'Order delivery update failed: ' . $e->getMessage());

            return redirect()->to(site_url('orders/view/' . $id))
                ->with('error', 'Could not mark the order as delivered. Please try again.');
        }

        return redirect()->to(site_url('orders/view/' . $id))
            ->with('success', 'Order marked as delivered successfully.');
    }
}
