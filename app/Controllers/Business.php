<?php

namespace App\Controllers;

use App\Models\BusinessModel;

class Business extends BaseController
{
    /**
     * Show the business registration page.
     */
    public function register()
    {
        $user = auth()->user();

        // Only logged-in sellers can register a business.
        if (!$user) {
            return redirect()->to('/login');
        }

        $businessModel = new BusinessModel();

        // Each seller can register only one business.
        $existingBusiness = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if ($existingBusiness) {
            return redirect()->to('/dashboard');
        }

        return view('business/register');
    }

    /**
     * Save the submitted business details.
     */
    public function store()
    {
        $user = auth()->user();

        // Only logged-in sellers can register a business.
        if (!$user) {
            return redirect()->to('/login');
        }

        $businessModel = new BusinessModel();

        // Prevent a seller from registering a second business.
        $existingBusiness = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if ($existingBusiness) {
            return redirect()->to('/dashboard');
        }

        // Get and clean the submitted values.
        $businessName = trim(
            (string) $this->request->getPost('business_name')
        );

        $businessType = trim(
            (string) $this->request->getPost('business_type')
        );

        $slug = strtolower(
            trim((string) $this->request->getPost('slug'))
        );

        $whatsappNumber = trim(
            (string) $this->request->getPost('whatsapp_number')
        );

        $address = trim(
            (string) $this->request->getPost('address')
        );

        // Validate the submitted information.
        $rules = [
            'business_name' => 'required|min_length[2]|max_length[150]',
            'business_type' => 'permit_empty|max_length[80]',
            'slug' => [
                'rules' => 'required|alpha_dash|min_length[3]|max_length[100]|is_unique[businesses.slug]',
                'errors' => [
                    'is_unique' => 'This public link is already taken. Please choose another slug.',
                ],
            ],
            'whatsapp_number' => 'required|max_length[20]',
            'address' => 'permit_empty|max_length[500]',
        ];

        $validationData = [
            'business_name' => $businessName,
            'business_type' => $businessType,
            'slug' => $slug,
            'whatsapp_number' => $whatsappNumber,
            'address' => $address,
        ];

        if (!$this->validateData($validationData, $rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Save the business.
        $businessModel->insert([
            'owner_user_id' => $user->id,
            'business_name' => $businessName,
            'business_type' => $businessType,
            'slug' => $slug,
            'whatsapp_number' => $whatsappNumber,
            'address' => $address,
            'is_active' => 1,
        ]);

        return redirect()
            ->to('/business/dashboard')
            ->with(
                'success',
                'Your business has been registered successfully!'
            );
    }

    /**
     * Show the registered business dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        $businessModel = new BusinessModel();

        // Get only the business owned by the logged-in seller.
        $business = $businessModel
            ->where('owner_user_id', $user->id)
            ->first();

        if (!$business) {
            return redirect()->to('/business/register');
        }

        return view('business/dashboard', [
            'business' => $business,
        ]);
    }
}