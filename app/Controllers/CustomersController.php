<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class CustomersController extends BaseController
{
    private CustomerModel $customers;

    public function __construct()
    {
        $this->customers = new CustomerModel();
    }

    public function index(): string
    {
        return $this->render('customers/index', [
            'title' => 'Customers',
            'customers' => $this->customers->orderBy('full_name')->findAll(),
        ]);
    }

    public function new(): string
    {
        return $this->render('customers/form', ['title' => 'Add Customer', 'customer' => null]);
    }

    public function create()
    {
        $data = $this->request->getPost(['full_name', 'email', 'phone']);
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        $this->customers->insert($data);

        return redirect()->to('/customers')->with('success', 'Customer added successfully.');
    }

    public function edit(int $id): string
    {
        return $this->render('customers/form', [
            'title' => 'Edit Customer',
            'customer' => $this->findOrFail($id),
        ]);
    }

    public function update(int $id)
    {
        $this->findOrFail($id);
        $data = $this->request->getPost(['full_name', 'email', 'phone']);
        if (! $this->validateData($data, $this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validationErrors());
        }

        $this->customers->update($id, $data);

        return redirect()->to('/customers')->with('success', 'Customer updated successfully.');
    }

    public function delete(int $id)
    {
        $this->findOrFail($id);
        $hasSales = db_connect()->table('sales')->where('customer_id', $id)->countAllResults() > 0;
        if ($hasSales) {
            return redirect()->to('/customers')->with('error', 'This customer has sales history and cannot be deleted.');
        }

        $this->customers->delete($id);

        return redirect()->to('/customers')->with('success', 'Customer deleted.');
    }

    private function rules(): array
    {
        return [
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'permit_empty|max_length[20]|regex_match[/^[0-9+() .-]+$/]',
        ];
    }

    private function findOrFail(int $id): array
    {
        $customer = $this->customers->find($id);
        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('Customer not found.');
        }

        return $customer;
    }
}
