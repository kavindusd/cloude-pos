<?php

class SaleController extends Controller
{
    // Cashier POS terminal
    public function cashier(): void
    {
        Auth::requirePermission('pos');
        $this->view('pos.cashier', [
            'title'     => 'POS — Cashier',
            'products'  => Product::all(),
            'customers' => Customer::forSelect(),
            'accounts'  => Account::all(),
        ]);
    }

    // Sales history list
    public function index(): void
    {
        Auth::requirePermission('sales');
        $this->view('sales.history', [
            'title' => 'Sales History',
            'sales' => Sale::all(),
        ]);
    }

    // Sale detail
    public function show(int $id): void
    {
        Auth::requirePermission('sales');
        $sale = Sale::findById($id);
        if (!$sale) {
            Session::flash('error', 'Sale not found.');
            $this->redirect('/sales');
            return;
        }
        $this->view('sales.show', [
            'title' => "Sale #{$id}",
            'sale'  => $sale,
            'items' => Sale::itemsFor($id),
        ]);
    }

    // Print invoice
    public function print(int $id): void
    {
        Auth::requirePermission('sales');
        $sale = Sale::findById($id);
        if (!$sale) {
            Session::flash('error', 'Sale not found.');
            $this->redirect('/sales');
            return;
        }
        $this->view('sales.print', [
            'title' => "Invoice #{$id}",
            'sale'  => $sale,
            'items' => Sale::itemsFor($id),
        ]);
    }

    // Checkout — POST from POS
    public function checkout(): void
    {
        Auth::requirePermission('pos');

        $customerId    = (int)$this->input('customer_id', 0);
        $accountId     = (int)$this->input('account_id', 0);
        $paymentMethod = $this->input('payment_method', 'cash');
        $discount      = (float)$this->input('discount', 0);
        $note          = trim($this->input('note', ''));

        $productIds = $_POST['product_id']  ?? [];
        $qtys       = $_POST['qty']         ?? [];
        $prices     = $_POST['unit_price']  ?? [];

        // Validate
        if (!in_array($paymentMethod, ['cash', 'bank', 'credit'])) {
            Session::flash('error', 'Invalid payment method.');
            $this->redirect('/pos');
            return;
        }

        if ($paymentMethod !== 'credit' && !$accountId) {
            Session::flash('error', 'Please select an account.');
            $this->redirect('/pos');
            return;
        }

        if ($paymentMethod === 'credit' && !$customerId) {
            Session::flash('error', 'Credit sales require a customer.');
            $this->redirect('/pos');
            return;
        }

        // Check customer credit limit
        if ($paymentMethod === 'credit' && $customerId) {
            $subtotal = 0;
            foreach ($productIds as $i => $pid) {
                $subtotal += (float)($prices[$i] ?? 0) * (int)($qtys[$i] ?? 0);
            }
            $saleTotal = max(0, $subtotal - $discount);
            $available = Customer::availableCredit($customerId);
            if ($available < $saleTotal) {
                Session::flash('error', sprintf(
                    'Customer credit limit exceeded. Available: LKR %s, Sale: LKR %s',
                    number_format($available, 2),
                    number_format($saleTotal, 2)
                ));
                $this->redirect('/pos');
                return;
            }
        }

        // Build items
        $items = [];
        foreach ($productIds as $i => $productId) {
            $productId = (int)$productId;
            $qty       = (int)($qtys[$i] ?? 0);
            $price     = (float)($prices[$i] ?? 0);
            if (!$productId || $qty <= 0) continue;

            // Check stock
            $product = Product::findById($productId);
            if (!$product || (int)$product['qty_total'] < $qty) {
                Session::flash('error', "Insufficient stock for: " . ($product['name'] ?? "Product #{$productId}"));
                $this->redirect('/pos');
                return;
            }

            $items[] = [
                'product_id' => $productId,
                'qty'        => $qty,
                'unit_price' => $price,
                'subtotal'   => $qty * $price,
            ];
        }

        if (empty($items)) {
            Session::flash('error', 'Cart is empty.');
            $this->redirect('/pos');
            return;
        }

        $header = [
            'customer_id'    => $customerId ?: null,
            'account_id'     => $accountId  ?: null,
            'payment_method' => $paymentMethod,
            'discount'       => $discount,
            'note'           => $note,
            'created_by'     => Auth::id(),
        ];

        try {
            $saleId = Sale::create($header, $items);
            ActivityLog::record(Auth::id(), 'sale_completed', 'sale', $saleId,
                count($items) . " items, LKR " . number_format(array_sum(array_column($items, 'subtotal')) - $discount, 2));

            // Check for low stock after sale and notify
            foreach ($items as $item) {
                $product = Product::findById($item['product_id']);
                if ($product && (int)$product['qty_total'] <= (int)$product['low_threshold']) {
                    Notification::notifyManagement(
                        "Low stock: {$product['name']} — only {$product['qty_total']} left.",
                        Auth::id()
                    );
                }
            }

            Session::flash('success', "Sale #{$saleId} completed successfully.");
            $this->redirect('/sales/' . $saleId);
        } catch (Exception $e) {
            Session::flash('error', 'Checkout failed. Please try again.');
            $this->redirect('/pos');
        }
    }
}