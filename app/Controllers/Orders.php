<?php



namespace App\Controllers;



use App\Models\BusinessModel;

use App\Models\OrderModel;

use App\Models\OrderItemModel;

use App\Models\OrderStatusHistoryModel;

use App\Models\PaymentModel;



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



        // Get filters.

        $status = trim((string) $this->request->getGet('status'));

        $search = trim((string) $this->request->getGet('q'));



        $dateFilter = trim((string) $this->request->getGet('date_filter'));

        $fromDate = trim((string) $this->request->getGet('from_date'));

        $toDate = trim((string) $this->request->getGet('to_date'));



        $paymentStatus = trim((string) $this->request->getGet('payment_status'));



        $dateError = '';



        // Validate order status.

        $allowedStatuses = [

            'pending',

            'accepted',

            'preparing',

            'ready',

            'delivered',

            'rejected',

        ];



        if (! in_array($status, $allowedStatuses, true)) {

            $status = '';
        }



        // Validate date filter.

        $allowedDateFilters = [

            '',

            'today',

            'yesterday',

            'last7',

            'last30',

            'custom',

        ];



        if (! in_array($dateFilter, $allowedDateFilters, true)) {

            $dateFilter = '';
        }



        // Validate payment status.

        $allowedPaymentStatuses = [

            'pending',

            'partial',

            'paid',

            'refunded',

        ];



        if (! in_array($paymentStatus, $allowedPaymentStatuses, true)) {

            $paymentStatus = '';
        }



        // Get order summary counts.

        // These counts are independent of all filters.

        $db = \Config\Database::connect();



        $orderCounts = $db->table('orders')

            ->select("

            COUNT(*) AS total_orders,

            COALESCE(SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END), 0) AS pending_orders,

            COALESCE(SUM(CASE WHEN order_status = 'accepted' THEN 1 ELSE 0 END), 0) AS accepted_orders,

            COALESCE(SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END), 0) AS delivered_orders,

            COALESCE(SUM(CASE WHEN order_status = 'rejected' THEN 1 ELSE 0 END), 0) AS rejected_orders

        ", false)

            ->where('business_id', $business['id'])

            ->get()

            ->getRowArray();



        // Prepare date boundaries.

        $startDate = null;

        $endDateExclusive = null;



        $today = new \DateTimeImmutable('today');



        switch ($dateFilter) {



            case 'today':



                $startDate = $today;

                $endDateExclusive = $today->modify('+1 day');



                break;



            case 'yesterday':



                $startDate = $today->modify('-1 day');

                $endDateExclusive = $today;



                break;



            case 'last7':



                $startDate = $today->modify('-6 days');

                $endDateExclusive = $today->modify('+1 day');



                break;



            case 'last30':



                $startDate = $today->modify('-29 days');

                $endDateExclusive = $today->modify('+1 day');



                break;



            case 'custom':



                $fromDateObject = \DateTimeImmutable::createFromFormat('!Y-m-d', $fromDate);



                $toDateObject = \DateTimeImmutable::createFromFormat('!Y-m-d', $toDate);



                if (

                    ! $fromDateObject ||

                    $fromDateObject->format('Y-m-d') !== $fromDate ||

                    ! $toDateObject ||

                    $toDateObject->format('Y-m-d') !== $toDate

                ) {



                    $dateError = 'Please select valid start and end dates.';
                } elseif ($fromDateObject > $toDateObject) {



                    $dateError = 'Start date cannot be later than end date.';
                } else {



                    $startDate = $fromDateObject;

                    $endDateExclusive = $toDateObject->modify('+1 day');
                }



                break;
        }



        // Build orders query for this business.

        $query = $orderModel

            ->where('business_id', $business['id']);



        // Apply order status filter.

        if ($status !== '') {

            $query->where('order_status', $status);
        }



        // Apply payment status filter.

        if ($paymentStatus !== '') {

            $query->where('payment_status', $paymentStatus);
        }



        // Apply search filter.

        if ($search !== '') {



            $query->groupStart()

                ->like('order_number', $search)

                ->orLike('customer_name', $search)

                ->orLike('customer_mobile', $search)

                ->groupEnd();
        }



        // Apply date filter.

        if ($startDate !== null && $endDateExclusive !== null) {



            $query->where(

                'order_date >=',

                $startDate->format('Y-m-d 00:00:00')

            );



            $query->where(

                'order_date <',

                $endDateExclusive->format('Y-m-d 00:00:00')

            );
        }



        // Get 20 orders per page.

        $orders = $query

            ->orderBy('order_date', 'DESC')

            ->paginate(20);



        return view('orders/index', [

            'pageTitle'     => 'Orders',

            'business'      => $business,

            'orders'        => $orders,

            'status'        => $status,

            'search'        => $search,

            'dateFilter'    => $dateFilter,

            'fromDate'      => $fromDate,

            'toDate'        => $toDate,

            'dateError'     => $dateError,

            'paymentStatus' => $paymentStatus,

            'pager'         => $orderModel->pager,

            'orderCounts'   => $orderCounts,

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



        $paymentModel = new PaymentModel();



        $payments = $paymentModel

            ->where('order_id', $order['id'])

            ->orderBy('payment_date', 'DESC')

            ->orderBy('id', 'DESC')

            ->findAll();



        // Retrieve order status history

        $statusHistoryModel = new OrderStatusHistoryModel();



        $statusHistory = $statusHistoryModel

            ->where('order_id', $id)

            ->orderBy('created_at', 'ASC')

            ->orderBy('id', 'ASC')

            ->findAll();



        return view('orders/view', [

            'pageTitle' => 'Order Details',

            'business'  => $business,

            'order'     => $order,

            'items'     => $items,

            'payments'  => $payments,

            'statusHistory' => $statusHistory,

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

        // Prepare the customer's WhatsApp number.
        $customerMobile = preg_replace('/\D/', '', $order['customer_mobile']);

        // Add India's country code if a 10-digit mobile number was entered.
        if (strlen($customerMobile) === 10) {
            $customerMobile = '91' . $customerMobile;
        } elseif (strlen($customerMobile) === 11 && $customerMobile[0] === '0') {
            $customerMobile = '91' . substr($customerMobile, 1);
        }

        // Update the order status and save history.
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

        // Check whether the customer's number is valid for WhatsApp.
        if (strlen($customerMobile) < 8 || strlen($customerMobile) > 15) {
            return redirect()->to(site_url('orders/view/' . $id))
                ->with('success', 'Order marked as delivered, but the customer mobile number is not valid for WhatsApp.');
        }

        // Prepare the delivery confirmation message.
        $whatsappMessage =
            "Hello " . $order['customer_name'] . ",\n\n" .
            "Your order " . $order['order_number'] . " has been successfully delivered.\n\n" .
            "Thank you for choosing us!";

        $whatsappUrl = 'https://wa.me/' . $customerMobile .
            '?text=' . rawurlencode($whatsappMessage);

        // Open WhatsApp with the prepared message.
        return redirect()->to($whatsappUrl);
    }

    public function recordPayment($id)

    {

        $business = (new BusinessModel())

            ->where('owner_user_id', auth()->id())

            ->first();



        if (! $business) {

            return redirect()->to(site_url('business/register'));
        }



        $db = \Config\Database::connect();

        $db->transBegin();



        try {

            $order = $db->query(

                'SELECT * FROM orders WHERE id = ? AND business_id = ? FOR UPDATE',

                [$id, $business['id']]

            )->getRowArray();



            if (! $order) {

                throw new \RuntimeException('Order not found.');
            }



            $amount = round((float) $this->request->getPost('amount'), 2);

            $method = trim((string) $this->request->getPost('payment_method'));

            $reference = trim((string) $this->request->getPost('reference_number'));

            $notes = trim((string) $this->request->getPost('notes'));

            $paymentDate = trim((string) $this->request->getPost('payment_date'));



            $allowedMethods = ['cash', 'upi', 'bank_transfer', 'other'];



            if (! is_numeric($this->request->getPost('amount')) || $amount <= 0) {

                throw new \RuntimeException('Enter a valid payment amount.');
            }



            if (! in_array($method, $allowedMethods, true)) {

                throw new \RuntimeException('Select a valid payment method.');
            }



            if ($paymentDate === '') {

                throw new \RuntimeException('Please select the payment date.');
            }



            $dateObject = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $paymentDate);



            if (! $dateObject || $dateObject->format('Y-m-d\TH:i') !== $paymentDate) {

                throw new \RuntimeException('Please enter a valid payment date.');
            }



            if (mb_strlen($reference) > 150 || mb_strlen($notes) > 2000) {

                throw new \RuntimeException('Reference number or notes are too long.');
            }



            $allowedPaymentStatuses = [

                'accepted',

                'preparing',

                'ready',

                'delivered',

            ];



            if (! in_array($order['order_status'], $allowedPaymentStatuses, true)) {

                throw new \RuntimeException(

                    'Payments can only be recorded for accepted, preparing, ready, or delivered orders.'

                );
            }



            $paymentModel = new PaymentModel();



            $payments = $paymentModel

                ->where('order_id', $id)

                ->findAll();



            $netPaid = 0;



            foreach ($payments as $payment) {

                if ($payment['transaction_type'] === 'payment') {

                    $netPaid += (float) $payment['amount'];
                } elseif ($payment['transaction_type'] === 'refund') {

                    $netPaid -= (float) $payment['amount'];
                }
            }



            $balance = round((float) $order['grand_total'] - $netPaid, 2);



            if ($amount > $balance + 0.001) {

                throw new \RuntimeException(

                    'Payment cannot exceed the outstanding balance of ₹' .

                        number_format(max(0, $balance), 2)

                );
            }



            $paymentModel->insert([

                'order_id'        => $id,

                'transaction_type' => 'payment',

                'amount'          => $amount,

                'payment_method'  => $method,

                'reference_number' => $reference ?: null,

                'notes'           => $notes ?: null,

                'payment_date'    => $dateObject->format('Y-m-d H:i:s'),

            ]);



            $netPaid = round($netPaid + $amount, 2);

            $this->updateOrderPaymentTotals($db, $id, $order['grand_total'], $netPaid, false);



            if ($db->transStatus() === false) {

                throw new \RuntimeException('Could not save the payment.');
            }



            $db->transCommit();



            return redirect()->to(site_url('orders/view/' . $id))

                ->with('success', 'Payment recorded successfully.');
        } catch (\Throwable $e) {

            $db->transRollback();



            log_message('error', 'Payment recording failed: ' . $e->getMessage());



            return redirect()->to(site_url('orders/view/' . $id))

                ->with('error', $e->getMessage());
        }
    }



    public function recordRefund($id)

    {

        $business = (new BusinessModel())

            ->where('owner_user_id', auth()->id())

            ->first();



        if (! $business) {

            return redirect()->to(site_url('business/register'));
        }



        $db = \Config\Database::connect();

        $db->transBegin();



        try {

            $order = $db->query(

                'SELECT * FROM orders WHERE id = ? AND business_id = ? FOR UPDATE',

                [$id, $business['id']]

            )->getRowArray();



            if (! $order) {

                throw new \RuntimeException('Order not found.');
            }



            $allowedRefundStatuses = [

                'accepted',

                'preparing',

                'ready',

                'delivered',

            ];



            if (! in_array($order['order_status'], $allowedRefundStatuses, true)) {

                throw new \RuntimeException(

                    'Refunds can only be recorded for accepted, preparing, ready, or delivered orders.'

                );
            }



            $amount = round((float) $this->request->getPost('amount'), 2);

            $method = trim((string) $this->request->getPost('payment_method'));

            $reference = trim((string) $this->request->getPost('reference_number'));

            $notes = trim((string) $this->request->getPost('notes'));

            $paymentDate = trim((string) $this->request->getPost('payment_date'));



            $allowedMethods = ['cash', 'upi', 'bank_transfer', 'other'];



            if (! is_numeric($this->request->getPost('amount')) || $amount <= 0) {

                throw new \RuntimeException('Enter a valid refund amount.');
            }



            if (! in_array($method, $allowedMethods, true)) {

                throw new \RuntimeException('Select a valid refund method.');
            }



            if ($paymentDate === '') {

                throw new \RuntimeException('Please select the refund date.');
            }



            $dateObject = \DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $paymentDate);



            if (! $dateObject || $dateObject->format('Y-m-d\TH:i') !== $paymentDate) {

                throw new \RuntimeException('Please enter a valid refund date.');
            }



            if (mb_strlen($reference) > 150 || mb_strlen($notes) > 2000) {

                throw new \RuntimeException('Reference number or notes are too long.');
            }



            $paymentModel = new PaymentModel();



            $payments = $paymentModel

                ->where('order_id', $id)

                ->findAll();



            $netPaid = 0;



            foreach ($payments as $payment) {

                if ($payment['transaction_type'] === 'payment') {

                    $netPaid += (float) $payment['amount'];
                } elseif ($payment['transaction_type'] === 'refund') {

                    $netPaid -= (float) $payment['amount'];
                }
            }



            $netPaid = round($netPaid, 2);



            if ($amount > $netPaid + 0.001) {

                throw new \RuntimeException(

                    'Refund cannot exceed the amount actually paid: ₹' .

                        number_format(max(0, $netPaid), 2)

                );
            }



            $paymentModel->insert([

                'order_id'         => $id,

                'transaction_type' => 'refund',

                'amount'           => $amount,

                'payment_method'   => $method,

                'reference_number' => $reference ?: null,

                'notes'            => $notes ?: null,

                'payment_date'     => $dateObject->format('Y-m-d H:i:s'),

            ]);



            $netPaid = round($netPaid - $amount, 2);



            $this->updateOrderPaymentTotals($db, $id, $order['grand_total'], $netPaid, true);



            if ($db->transStatus() === false) {

                throw new \RuntimeException('Could not save the refund.');
            }



            $db->transCommit();



            return redirect()->to(site_url('orders/view/' . $id))

                ->with('success', 'Refund recorded successfully.');
        } catch (\Throwable $e) {

            $db->transRollback();



            log_message('error', 'Refund recording failed: ' . $e->getMessage());



            return redirect()->to(site_url('orders/view/' . $id))

                ->with('error', $e->getMessage());
        }
    }



    private function updateOrderPaymentTotals(

        $db,

        $orderId,

        $grandTotal,

        $netPaid,

        $hasRefund

    ): void {

        $grandTotal = round((float) $grandTotal, 2);

        $netPaid = round(max(0, (float) $netPaid), 2);



        $balance = round(max(0, $grandTotal - $netPaid), 2);



        if ($netPaid <= 0 && $hasRefund) {

            $paymentStatus = 'refunded';
        } elseif ($netPaid <= 0) {

            $paymentStatus = 'pending';
        } elseif ($balance <= 0) {

            $paymentStatus = 'paid';
        } else {

            $paymentStatus = 'partial';
        }



        $db->table('orders')

            ->where('id', $orderId)

            ->update([

                'amount_paid'    => $netPaid,

                'balance_amount' => $balance,

                'payment_status' => $paymentStatus,

            ]);
    }
}
