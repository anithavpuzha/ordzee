<?php

namespace App\Controllers;

use App\Models\BusinessModel;
use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $productModel = new ProductModel();

        $products = $productModel
            ->where('business_id', $business['id'])
            ->orderBy('product_name', 'ASC')
            ->findAll();

        return view('products/index', [
            'pageTitle'       => 'Products',
            'pageDescription' => 'Manage your reusable product catalogue.',
            'activePage'      => 'products',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'products'        => $products,
        ]);
    }

    public function create()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        return view('products/create', [
            'pageTitle'       => 'Add Product',
            'pageDescription' => 'Add a reusable product to your catalogue.',
            'activePage'      => 'products',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
        ]);
    }

    public function store()
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $rules = [
            'product_name' => 'required|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $productModel = new ProductModel();

        $productModel->insert([
            'business_id'  => $business['id'],
            'product_name' => trim($this->request->getPost('product_name')),
            'is_active'    => 1,
        ]);

        return redirect()->to('/products')
            ->with('success', 'Product added successfully.');
    }
      
    public function toggleStatus($id)
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $productModel = new ProductModel();

        $product = $productModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $product) {
            return redirect()->to('/products')
                ->with('error', 'Product not found.');
        }

        $newStatus = $product['is_active'] ? 0 : 1;

        $productModel->update($product['id'], [
            'is_active' => $newStatus,
        ]);

        $message = $newStatus
            ? 'Product activated successfully.'
            : 'Product deactivated successfully.';

        return redirect()->to('/products')
            ->with('success', $message);
    }

    public function edit($id)
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $productModel = new ProductModel();

        $product = $productModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $product) {
            return redirect()->to('/products')
                ->with('error', 'Product not found.');
        }

        return view('products/edit', [
            'pageTitle'       => 'Edit Product',
            'pageDescription' => 'Update your product catalogue.',
            'activePage'      => 'products',
            'sellerName'      => $user->email ?? 'Seller',
            'business'        => $business,
            'product'         => $product,
        ]);
    }
            
    public function update($id)
    {
        if (! auth()->loggedIn()) {
            return redirect()->to(url_to('login'));
        }

        $user = auth()->user();

        $businessModel = new BusinessModel();

        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (! $business) {
            return redirect()->to('/business/register');
        }

        $productModel = new ProductModel();

        $product = $productModel
            ->where('id', $id)
            ->where('business_id', $business['id'])
            ->first();

        if (! $product) {
            return redirect()->to('/products')
                ->with('error', 'Product not found.');
        }

        $rules = [
            'product_name' => 'required|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $productModel->update($product['id'], [
            'product_name' => trim($this->request->getPost('product_name')),
        ]);

        return redirect()->to('/products')
            ->with('success', 'Product updated successfully.');
    }
}