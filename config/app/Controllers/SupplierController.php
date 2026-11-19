<?php

class SupplierController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('suppliers');
        $this->view('suppliers.index', [
            'title'     => 'Suppliers',
            'suppliers' => Supplier::all(),
        ]);
    }

    public function create(): void
    {
        Auth::requirePermission('suppliers');
        $products = Product::forSelect();
        $this->view('suppliers.form', [
            'title'    => 'Add Supplier',
            'products' => $products
        ]);
    }

    public function store(): void
    {
        Auth::requirePermission('suppliers');

        $data  = $this->collectInput();
        $error = $this->validate($data);

        if ($error) {
            $this->view('suppliers.form', [
                'title'    => 'Add Supplier',
                'old'      => $data,
                'products' => Product::forSelect(),
                'error'    => $error,
            ]);
            return;
        }

        $id = Supplier::create($data);
        
        // Associate products
        if (!empty($data['product_ids'])) {
            $db = Database::getInstance()->getConnection();
            $placeholders = implode(',', array_fill(0, count($data['product_ids']), '?'));
            $db->prepare("UPDATE products SET supplier_id = ? WHERE id IN ($placeholders)")
               ->execute(array_merge([$id], $data['product_ids']));
        }

        ActivityLog::record(Auth::id(), 'supplier_created', 'supplier', $id, $data['name']);
        Session::flash('success', "Supplier \"{$data['name']}\" added.");
        $this->redirect('/suppliers');
    }

    public function edit(int $id): void
    {
        Auth::requirePermission('suppliers');
        $supplier = Supplier::findById($id);
        if (!$supplier) {
            Session::flash('error', 'Supplier not found.');
            $this->redirect('/suppliers');
            return;
        }
        $this->view('suppliers.form', [
            'title'    => 'Edit Supplier',
            'supplier' => $supplier,
            'products' => Product::forSelect(),
            'current_product_ids' => array_column(
                Database::getInstance()->getConnection()->query("SELECT id FROM products WHERE supplier_id = $id")->fetchAll(), 
                'id'
            )
        ]);
    }

    public function update(int $id): void
    {
        Auth::requirePermission('suppliers');

        $supplier = Supplier::findById($id);
        if (!$supplier) {
            Session::flash('error', 'Supplier not found.');
            $this->redirect('/suppliers');
            return;
        }

        $data  = $this->collectInput();
        $error = $this->validate($data);

        if ($error) {
            $this->view('suppliers.form', [
                'title'    => 'Edit Supplier',
                'supplier' => array_merge($supplier, $data),
                'products' => Product::forSelect(),
                'current_product_ids' => $data['product_ids'] ?? [],
                'error'    => $error,
            ]);
            return;
        }

        Supplier::update($id, $data);

        // Update associations: 
        // 1. Clear old associations
        $db = Database::getInstance()->getConnection();
        $db->prepare("UPDATE products SET supplier_id = NULL WHERE supplier_id = ?")->execute([$id]);
        
        // 2. Set new associations
        if (!empty($data['product_ids'])) {
            $placeholders = implode(',', array_fill(0, count($data['product_ids']), '?'));
            $db->prepare("UPDATE products SET supplier_id = ? WHERE id IN ($placeholders)")
               ->execute(array_merge([$id], $data['product_ids']));
        }

        ActivityLog::record(Auth::id(), 'supplier_updated', 'supplier', $id, $data['name']);
        Session::flash('success', "Supplier \"{$data['name']}\" updated.");
        $this->redirect('/suppliers');
    }

    public function toggle(int $id): void
    {
        Auth::requirePermission('suppliers');
        $supplier = Supplier::findById($id);
        if ($supplier) {
            Supplier::toggleActive($id);
            $state = $supplier['active'] ? 'deactivated' : 'activated';
            ActivityLog::record(Auth::id(), "supplier_{$state}", 'supplier', $id, $supplier['name']);
            Session::flash('success', "Supplier \"{$supplier['name']}\" {$state}.");
        }
        $this->redirect('/suppliers');
    }

    // -------------------------------------------------------------------------

    private function collectInput(): array
    {
        return [
            'name'        => trim($this->input('name', '')),
            'phone'       => trim($this->input('phone', '')),
            'email'       => trim($this->input('email', '')),
            'address'     => trim($this->input('address', '')),
            'product_ids' => $this->input('product_ids', []),
        ];
    }

    private function validate(array $data): ?string
    {
        if (empty($data['name'])) return 'Supplier name is required.';
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }
        return null;
    }
}