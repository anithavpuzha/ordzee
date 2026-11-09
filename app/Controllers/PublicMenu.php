<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\DailyMenuModel;
use App\Models\DailyMenuItemModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PublicMenu extends BaseController
{
    public function show($slug)
    {
        // Find the business using its public slug.
        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('slug', $slug)
            ->first();

        if (! $business || ! $business['is_active']) {
            throw PageNotFoundException::forPageNotFound(
                'Business menu not found.'
            );
        }

        // Find the business's permanent menu.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            throw PageNotFoundException::forPageNotFound(
                'Menu not found.'
            );
        }

        // Get only currently available menu items.
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
}