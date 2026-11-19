<?php

class CustomerController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('customers');
        $this->view('customers.index', [
            'title'     => 'Customers',
            'customers' => Customer::all(),
        ]);
    }

    public function create(): void
    {
        Auth::requirePermission('customers');
        $this->view('customers.form', ['title' => 'Add Customer']);
    }

    public function store(): void
    {
        Auth::requirePermission('customers');

        $data  = $this->collectInput();
        $error = $this->validate($data);

        if ($error) {
            $this->view('customers.form', [
                'title' => 'Add Customer',
                'old'   => $data,
                'error' => $error,
            ]);
            return;
        }

        $id = Customer::create($data);
        ActivityLog::record(Auth::id(), 'customer_created', 'customer', $id, $data['name']);
        Session::flash('success', "Customer \"{$data['name']}\" added.");
        $this->redirect('/customers');
    }

    public function edit(int $id): void
    {
        Auth::requirePermission('customers');
        $customer = Customer::findById($id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/customers');
            return;
        }
        $this->view('customers.form', [
            'title'    => 'Edit Customer',
            'customer' => $customer,
        ]);
    }

    public function update(int $id): void
    {
        Auth::requirePermission('customers');

        $customer = Customer::findById($id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/customers');
            return;
        }

        $data  = $this->collectInput();
        $error = $this->validate($data);

        if ($error) {
            $this->view('customers.form', [
                'title'    => 'Edit Customer',
                'customer' => array_merge($customer, $data),
                'error'    => $error,
            ]);
            return;
        }

        Customer::update($id, $data);
        ActivityLog::record(Auth::id(), 'customer_updated', 'customer', $id, $data['name']);
        Session::flash('success', "Customer \"{$data['name']}\" updated.");
        $this->redirect('/customers');
    }

    public function toggle(int $id): void
    {
        Auth::requirePermission('customers');
        $customer = Customer::findById($id);
        if ($customer) {
            Customer::toggleActive($id);
            $state = $customer['active'] ? 'deactivated' : 'activated';
            ActivityLog::record(Auth::id(), "customer_{$state}", 'customer', $id, $customer['name']);
            Session::flash('success', "Customer \"{$customer['name']}\" {$state}.");
        }
        $this->redirect('/customers');
    }

    // ------------------------------------------------------------------

    private function collectInput(): array
    {
        return [
            'name'         => trim($this->input('name', '')),
            'phone'        => trim($this->input('phone', '')),
            'email'        => trim($this->input('email', '')),
            'address'      => trim($this->input('address', '')),
            'type'         => $this->input('type', 'retail'),
            'credit_limit' => (float)$this->input('credit_limit', 0),
        ];
    }

    private function validate(array $data): ?string
    {
        if (empty($data['name'])) return 'Customer name is required.';
        if (!in_array($data['type'], ['retail', 'wholesale'])) return 'Invalid customer type.';
        if ($data['credit_limit'] < 0) return 'Credit limit cannot be negative.';
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }
        return null;
    }
}