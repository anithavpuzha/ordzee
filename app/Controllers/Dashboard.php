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

        // Show the existing seller dashboard.
        return view('dashboard/index', [
            'pageTitle'       => 'Dashboard',
            'pageDescription' => 'Your business overview and daily operations.',
            'activePage'      => 'dashboard',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
        ]);
    }
}