<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\DailyMenuModel;
use App\Models\DailyMenuItemModel;
use App\Models\ProductModel;

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

        // Find this business's permanent menu.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('business_id', $business['id'])
            ->first();

        // Create a menu only if one does not already exist.
        if (! $menu) {
            $menuId = $dailyMenuModel->insert([
                'business_id' => $business['id'],
                'menu_title'  => 'Menu',
                'status'      => 'draft',
            ]);

            if (! $menuId) {
                return redirect()->back()
                    ->with('error', 'Unable to create your menu. Please try again.');
            }
        } else {
            // Keep the permanent menu title fixed as Menu.
            if ($menu['menu_title'] !== 'Menu') {
                $dailyMenuModel->update($menu['id'], [
                    'menu_title' => 'Menu',
                ]);
            }

            $menuId = $menu['id'];
        }

        // Open the permanent menu.
        return redirect()->to('/daily-menu/view/' . $menuId);
    }
       
    public function create()
    {
        // Permanent menus are opened from the Daily Menu page.
        return redirect()->to('/daily-menu');
    }

    public function store()
    {
        // Creating additional menus is no longer supported.
        return redirect()->to('/daily-menu');
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

        // Get active products from this business's catalogue.
        $productModel = new ProductModel();

        $products = $productModel
            ->where('business_id', $business['id'])
            ->where('is_active', 1)
            ->orderBy('product_name', 'ASC')
            ->findAll();    

        return view('daily_menu/view', [
            'pageTitle'       => 'Menu',
            'pageDescription' => 'Manage your permanent business menu.',
            'activePage'      => 'daily-menu',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'menu'            => $menu,
            'items'           => $items,
            'products'        => $products,
        ]);
    }
      
    public function addItem($menuId)
    {
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

        // Check that this menu belongs to the seller.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $menuId)
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu not found.');
        }

        // Validate the submitted details.
        $rules = [
            'product_id' => 'required|integer|greater_than[0]',
            'unit'       => 'required|max_length[50]',
            'price'      => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[99999999.99]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Find the selected active product in this business.
        $productModel = new ProductModel();

        $product = $productModel
            ->where('id', $this->request->getPost('product_id'))
            ->where('business_id', $business['id'])
            ->where('is_active', 1)
            ->first();

        if (! $product) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Selected product is not available.');
        }

        // Find the next display order.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $sortOrder = $dailyMenuItemModel
            ->where('daily_menu_id', $menu['id'])
            ->countAllResults();

        // Save a snapshot of the product name, unit and price.
        $dailyMenuItemModel->insert([
            'daily_menu_id' => $menu['id'],
            'product_id'    => $product['id'],
            'product_name'  => $product['product_name'],
            'unit'          => trim($this->request->getPost('unit')),
            'price'         => $this->request->getPost('price'),
            'is_available'  => 1,
            'sort_order'    => $sortOrder,
        ]);

        return redirect()->to('/daily-menu/view/' . $menu['id'])
            ->with('success', 'Product added to daily menu successfully.');
    }
        
    public function editItem($itemId)
    {
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

        // Find the selected menu item.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $item = $dailyMenuItemModel
            ->where('id', $itemId)
            ->first();

        if (! $item) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Verify that its menu belongs to this seller.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $item['daily_menu_id'])
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        return view('daily_menu/edit_item', [
            'pageTitle'       => 'Edit Menu Item',
            'pageDescription' => 'Update the unit and price of this menu item.',
            'activePage'      => 'daily-menu',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'menu'            => $menu,
            'item'            => $item,
        ]);
    }
        
    public function updateItem($itemId)
    {
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

        // Find the menu item.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $item = $dailyMenuItemModel
            ->where('id', $itemId)
            ->first();

        if (! $item) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Verify menu ownership.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $item['daily_menu_id'])
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Validate the updated unit and price.
        $rules = [
            'unit'  => 'required|max_length[50]',
            'price' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[99999999.99]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Update only this daily menu item.
        $dailyMenuItemModel->update($item['id'], [
            'unit'  => trim($this->request->getPost('unit')),
            'price' => $this->request->getPost('price'),
        ]);

        return redirect()->to('/daily-menu/view/' . $menu['id'])
            ->with('success', 'Menu item updated successfully.');
    }

    public function toggleItemAvailability($itemId)
    {
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

        // Find the menu item.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $item = $dailyMenuItemModel
            ->where('id', $itemId)
            ->first();

        if (! $item) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Verify that the menu belongs to this seller.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $item['daily_menu_id'])
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Toggle availability.
        $newStatus = $item['is_available'] ? 0 : 1;

        $dailyMenuItemModel->update($item['id'], [
            'is_available' => $newStatus,
        ]);

        $message = $newStatus
            ? 'Menu item activated successfully.'
            : 'Menu item deactivated successfully.';

        return redirect()->to('/daily-menu/view/' . $menu['id'])
            ->with('success', $message);
    }

    public function deleteItem($itemId)
    {
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

        // Find the menu item.
        $dailyMenuItemModel = new DailyMenuItemModel();

        $item = $dailyMenuItemModel
            ->where('id', $itemId)
            ->first();

        if (! $item) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Verify that the menu belongs to this seller.
        $dailyMenuModel = new DailyMenuModel();

        $menu = $dailyMenuModel
            ->where('id', $item['daily_menu_id'])
            ->where('business_id', $business['id'])
            ->first();

        if (! $menu) {
            return redirect()->to('/daily-menu')
                ->with('error', 'Menu item not found.');
        }

        // Delete only this daily menu item.
        $dailyMenuItemModel->delete($item['id']);

        return redirect()->to('/daily-menu/view/' . $menu['id'])
            ->with('success', 'Menu item deleted successfully.');
    }
}