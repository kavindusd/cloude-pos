<?php

class ProductController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('products');
        $products   = Product::all();
        $this->view('products.index', [
            'title'    => 'Products',
            'products' => $products,
        ]);
    }

    public function create(): void
    {
        Auth::requirePermission('products');
        $categories = Category::forSelect();
        $suppliers  = Supplier::forSelect();
        $this->view('products.form', [
            'title'      => 'Add Product',
            'categories' => $categories,
            'suppliers'  => $suppliers,
        ]);
    }

    public function store(): void
    {
        Auth::requirePermission('products');

        $data = $this->collectInput();
        $error = $this->validate($data);

        if ($error) {
            $this->view('products.form', [
                'title'      => 'Add Product',
                'categories' => Category::forSelect(),
                'suppliers'  => Supplier::forSelect(),
                'old'        => $data,
                'error'      => $error,
            ]);
            return;
        }

        // Barcode uniqueness
        if (!empty($data['barcode']) && Product::barcodeExists($data['barcode'])) {
            $this->view('products.form', [
                'title'      => 'Add Product',
                'categories' => Category::forSelect(),
                'suppliers'  => Supplier::forSelect(),
                'old'        => $data,
                'error'      => 'That barcode is already in use.',
            ]);
            return;
        }

        $id = Product::create($data);
        ActivityLog::record(Auth::id(), 'product_created', 'product', $id, $data['name']);
        Session::flash('success', "Product \"{$data['name']}\" created.");
        $this->redirect('/products');
    }

    public function edit(int $id): void
    {
        Auth::requirePermission('products');
        $product = Product::findById($id);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect('/products');
            return;
        }
        $this->view('products.form', [
            'title'      => 'Edit Product',
            'categories' => Category::forSelect(),
            'suppliers'  => Supplier::forSelect(),
            'product'    => $product,
        ]);
    }

    public function update(int $id): void
    {
        Auth::requirePermission('products');

        $product = Product::findById($id);
        if (!$product) {
            Session::flash('error', 'Product not found.');
            $this->redirect('/products');
            return;
        }

        $data          = $this->collectInput();
        $data['active'] = (int)$this->input('active', 1);
        $error         = $this->validate($data);

        if ($error) {
            $this->view('products.form', [
                'title'      => 'Edit Product',
                'categories' => Category::forSelect(),
                'suppliers'  => Supplier::forSelect(),
                'product'    => array_merge($product, $data),
                'error'      => $error,
            ]);
            return;
        }

        if (!empty($data['barcode']) && Product::barcodeExists($data['barcode'], $id)) {
            $this->view('products.form', [
                'title'      => 'Edit Product',
                'categories' => Category::forSelect(),
                'suppliers'  => Supplier::forSelect(),
                'product'    => array_merge($product, $data),
                'error'      => 'That barcode is already in use.',
            ]);
            return;
        }

        Product::update($id, $data);
        ActivityLog::record(Auth::id(), 'product_updated', 'product', $id, $data['name']);
        Session::flash('success', "Product \"{$data['name']}\" updated.");
        $this->redirect('/products');
    }

    public function toggle(int $id): void
    {
        Auth::requirePermission('products');
        $product = Product::findById($id);
        if ($product) {
            Product::toggleActive($id);
            $state = $product['active'] ? 'deactivated' : 'activated';
            ActivityLog::record(Auth::id(), "product_{$state}", 'product', $id, $product['name']);
            Session::flash('success', "Product \"{$product['name']}\" {$state}.");
        }
        $this->redirect('/products');
    }

    public function delete(int $id): void
    {
        Auth::requirePermission('products');
        $product = Product::findById($id);
        if ($product) {
            if ((int)$product['qty_total'] > 0) {
                Session::flash('error', 'Cannot delete a product that has stock. Deactivate it instead.');
                $this->redirect('/products');
                return;
            }
            Product::delete($id);
            ActivityLog::record(Auth::id(), 'product_deleted', 'product', $id, $product['name']);
            Session::flash('success', "Product \"{$product['name']}\" deleted.");
        }
        $this->redirect('/products');
    }

    // -------------------------------------------------------------------------

    private function collectInput(): array
    {
        return [
            'category_id'     => (int)$this->input('category_id', 0),
            'supplier_id'     => (int)$this->input('supplier_id', 0),
            'name'            => trim($this->input('name', '')),
            'barcode'         => trim($this->input('barcode', '')),
            'buying_price'    => (float)$this->input('buying_price', 0),
            'price_retail'    => (float)$this->input('price_retail', 0),
            'price_wholesale' => (float)$this->input('price_wholesale', 0),
            'low_threshold'   => (int)$this->input('low_threshold', 5),
            'active'          => (int)$this->input('active', 1),
        ];
    }

    private function validate(array $data): ?string
    {
        if (empty($data['name'])) return 'Product name is required.';
        if ($data['buying_price'] < 0)    return 'Buying price cannot be negative.';
        if ($data['price_retail'] < 0)    return 'Retail price cannot be negative.';
        if ($data['price_wholesale'] < 0) return 'Wholesale price cannot be negative.';
        if ($data['low_threshold'] < 0)   return 'Low-stock threshold cannot be negative.';
        return null;
    }
}