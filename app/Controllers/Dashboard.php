<?php

namespace App\Controllers;

use App\Models\BusinessModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Check whether the seller is logged in.
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        // Find the business owned by this seller.
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        // If no business is registered, redirect to registration.
        if (! $business) {
            return redirect()->to('/business/register');
        }

        $db = \Config\Database::connect();

        $businessId = $business['id'];

        // Date boundaries.
        $todayStart = date('Y-m-d 00:00:00');
        $tomorrowStart = date('Y-m-d 00:00:00', strtotime('tomorrow'));

        // Start of this week (Monday).
        $weekStart = date(
            'Y-m-d 00:00:00',
            strtotime('monday this week')
        );

        // Start of this month.
        $monthStart = date('Y-m-01 00:00:00');

        /*
         * Calculate sales using the date an order was marked
         * as delivered in order_status_history.
         *
         * Only delivered orders belonging to this business
         * are included.
         */
        $getDeliveredSales = static function (
            string $startDate,
            string $endDate
        ) use ($db, $businessId): float {

            $sql = "
                SELECT COALESCE(SUM(o.grand_total), 0) AS total_sales

                FROM orders o

                INNER JOIN (
                    SELECT
                        order_id,
                        MAX(created_at) AS delivered_at

                    FROM order_status_history

                    WHERE new_status = 'delivered'

                    GROUP BY order_id

                ) history ON history.order_id = o.id

                WHERE o.business_id = ?
                  AND o.order_status = 'delivered'
                  AND history.delivered_at >= ?
                  AND history.delivered_at < ?
            ";

            $result = $db->query(
                $sql,
                [$businessId, $startDate, $endDate]
            )->getRowArray();

            return (float) ($result['total_sales'] ?? 0);
        };

        // Sales figures.
        $todaySales = $getDeliveredSales(
            $todayStart,
            $tomorrowStart
        );

        $weeklySales = $getDeliveredSales(
            $weekStart,
            $tomorrowStart
        );

        $monthlySales = $getDeliveredSales(
            $monthStart,
            $tomorrowStart
        );

        // Total orders received by this business.
        $totalOrders = $db->table('orders')
            ->where('business_id', $businessId)
            ->countAllResults();

        // Orders received today.
        $todayOrders = $db->table('orders')
            ->where('business_id', $businessId)
            ->where('order_date >=', $todayStart)
            ->where('order_date <', $tomorrowStart)
            ->countAllResults();

        // Orders waiting for seller action.
        $pendingOrders = $db->table('orders')
            ->where('business_id', $businessId)
            ->where('order_status', 'pending')
            ->countAllResults();

        // Outstanding customer payment balance.
        $paymentResult = $db->table('orders')
            ->selectSum('balance_amount', 'total_balance')
            ->where('business_id', $businessId)
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get()
            ->getRowArray();

        $pendingPayments = (float) ($paymentResult['total_balance'] ?? 0);

        // Get the five most recent orders.
        $recentOrders = $db->table('orders')
            ->select([
                'id',
                'order_number',
                'customer_name',
                'grand_total',
                'order_status',
                'payment_status',
                'order_date',
            ])
            ->where('business_id', $businessId)
            ->orderBy('order_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('dashboard/index', [
            'pageTitle'       => 'Dashboard',
            'pageDescription' => 'Your business overview and daily operations.',
            'activePage'      => 'dashboard',

            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,

            'totalOrders'     => $totalOrders,
            'todayOrders'     => $todayOrders,
            'pendingOrders'   => $pendingOrders,
            'pendingPayments' => $pendingPayments,

            'todaySales'      => $todaySales,
            'weeklySales'     => $weeklySales,
            'monthlySales'    => $monthlySales,

            'recentOrders'    => $recentOrders,
        ]);
    }
}