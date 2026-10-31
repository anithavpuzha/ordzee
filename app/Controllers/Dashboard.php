<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // Prevent guests from accessing the seller dashboard.
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        return view('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'sellerName' => $user->email ?? 'Seller',
        ]);
    }
}