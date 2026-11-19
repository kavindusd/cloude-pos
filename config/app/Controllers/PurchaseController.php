<?php

class PurchaseController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('purchases');
        $this->view('purchases.index', [
            'title'     => 'Purchases',
            'purchases' => Purchase::all(),
        ]);
    }

    public function create(): void
    {
        Auth::requirePermission('purchases');
        $this->view('purchases.create', [
            'title'     => 'New Purchase',
            'suppliers' => Supplier::forSelect(),
            'products'  => Product::forSelect(),
            'accounts'  => Account::all(),
        ]);
    }

    public function store(): void
    {
        Auth::requirePermission('purchases');

        $supplierId    = (int)$this->input('supplier_id');
        $paymentMethod = $this->input('payment_method', 'cash');
        $accountId     = (int)$this->input('account_id', 0);
        $note          = trim($this->input('note', ''));

        // Line items come as parallel arrays
        $productIds = $_POST['product_id']  ?? [];
        $qtys       = $_POST['qty']         ?? [];
        $unitCosts  = $_POST['unit_cost']   ?? [];

        // Basic validation
        if (!$supplierId) {
            Session::flash('error', 'Please select a supplier.');
            $this->redirect('/purchases/create');
            return;
        }

        if (!in_array($paymentMethod, ['cash', 'bank', 'credit'])) {
            Session::flash('error', 'Invalid payment method.');
            $this->redirect('/purchases/create');
            return;
        }

        if ($paymentMethod !== 'credit' && !$accountId) {
            Session::flash('error', 'Please select an account for cash/bank payment.');
            $this->redirect('/purchases/create');
            return;
        }

        // Build items array
        $items = [];
        foreach ($productIds as $i => $productId) {
            $productId = (int)$productId;
            $qty       = (int)($qtys[$i] ?? 0);
            $unitCost  = (float)($unitCosts[$i] ?? 0);

            if (!$productId || $qty <= 0 || $unitCost < 0) continue;

            $items[] = [
                'product_id' => $productId,
                'qty'        => $qty,
                'unit_cost'  => $unitCost,
                'subtotal'   => $qty * $unitCost,
            ];
        }

        if (empty($items)) {
            Session::flash('error', 'Add at least one product line item.');
            $this->redirect('/purchases/create');
            return;
        }

        $header = [
            'supplier_id'    => $supplierId,
            'account_id'     => $accountId ?: null,
            'payment_method' => $paymentMethod,
            'note'           => $note,
            'created_by'     => Auth::id(),
        ];

        try {
            $purchaseId = Purchase::create($header, $items);
            ActivityLog::record(Auth::id(), 'purchase_request_created', 'purchase', $purchaseId,
                "Supplier #{$supplierId}, " . count($items) . " items");
            Session::flash('success', "Purchase Request #{$purchaseId} recorded as Pending.");
            $this->redirect('/purchases/' . $purchaseId);
        } catch (Exception $e) {
            Session::flash('error', 'Failed to save purchase. Please try again.');
            $this->redirect('/purchases/create');
        }
    }

    public function show(int $id): void
    {
        Auth::requirePermission('purchases');
        $purchase = Purchase::findById($id);
        if (!$purchase) {
            Session::flash('error', 'Purchase not found.');
            $this->redirect('/purchases');
            return;
        }
        $this->view('purchases.show', [
            'title'    => "Purchase #{$id}",
            'purchase' => $purchase,
            'items'    => Purchase::itemsFor($id),
        ]);
    }

    public function print(int $id): void
    {
        Auth::requirePermission('purchases');
        $purchase = Purchase::findById($id);
        if (!$purchase) {
            Session::flash('error', 'Purchase not found.');
            $this->redirect('/purchases');
            return;
        }
        $this->view('purchases.print', [
            'title'    => "Purchase Order #{$id}",
            'purchase' => $purchase,
            'items'    => Purchase::itemsFor($id),
        ]);
    }

    public function receive(int $id): void
    {
        Auth::requirePermission('purchases');
        if (Purchase::receive($id, Auth::id())) {
            ActivityLog::record(Auth::id(), 'purchase_received', 'purchase', $id);
            Session::flash('success', "Purchase #{$id} has been marked as Received. Stock and accounts updated.");
        } else {
            Session::flash('error', "Failed to mark purchase as received.");
        }
        $this->redirect('/purchases/' . $id);
    }

    public function cancel(int $id): void
    {
        Auth::requirePermission('purchases');
        if (Purchase::cancel($id)) {
            ActivityLog::record(Auth::id(), 'purchase_cancelled', 'purchase', $id);
            Session::flash('success', "Purchase #{$id} has been cancelled.");
        } else {
            Session::flash('error', "Failed to cancel purchase.");
        }
        $this->redirect('/purchases/' . $id);
    }
}