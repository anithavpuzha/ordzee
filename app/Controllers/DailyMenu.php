<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\DailyMenuModel;
use App\Models\DailyMenuItemModel;

class DailyMenu extends BaseController
{
    public function index()
    {
        // Check whether the seller is logged in.
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        // Find the seller's business.
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        // Find this business's daily menus.
        $dailyMenuModel = new DailyMenuModel();

        $menus = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->orderBy('menu_date', 'DESC')
            ->findAll();

        return view('daily_menu/index', [
            'pageTitle'       => 'Daily Menu',
            'pageDescription' => 'Create and manage your daily menus.',
            'activePage'      => 'daily-menu',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'menus'           => $menus,
        ]);
    }

    public function create()
{
    $user = auth()->user();

    $businessModel = new BusinessModel();

    $business = $businessModel
        ->where('owner_user_id', $user->id)
        ->first();

    if (! $business) {
        return redirect()->to('/business/register');
    }

    return view('daily_menu/create', [
        'pageTitle'       => 'Create Daily Menu',
        'pageDescription' => 'Create a menu for a selected date.',
        'activePage'      => 'daily-menu',
        'sellerName'      => $user->email ?? 'Seller',
        'business'        => $business,
    ]);
}


    public function store()
    {
        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $rules = [
            'menu_date' => 'required|valid_date[Y-m-d]',
            'menu_title' => 'required|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $dailyMenuModel = new DailyMenuModel();

        // Check whether this business already has a menu for this date.
        $existingMenu = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->where('menu_date', $this->request->getPost('menu_date'))
            ->first();

        if ($existingMenu) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A menu already exists for this date.');
        }

        $dailyMenuModel->insert([
            'business_id' => $business['id'],
            'menu_date'   => $this->request->getPost('menu_date'),
            'menu_title'  => trim($this->request->getPost('menu_title')),
            'status'      => 'draft',
        ]);

        return redirect()->to('/daily-menu')
            ->with('success', 'Daily menu created successfully.');
    }

    public function view($id)
    {
        $user = auth()->user();

        // Find the seller's business.
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        // Find this menu, ensuring it belongs to this seller's business.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu not found.');
        }

        // Get items belonging to this menu.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $items = $dailyMenuItemModel
            ->where('daily_menu_id', $menu['id'])
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        return view('daily_menu/view', [
            'pageTitle'       => $menu['menu_title'],
            'pageDescription' => 'Manage items in your daily menu.',
            'activePage'      => 'daily-menu',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'menu'            => $menu,
            'items'           => $items,
        ]);
    }
}