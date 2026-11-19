<?php

class StockController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('stock');
        
        $products = Product::all();
        $batches  = Stock::batches();
        
        // Stats
        $lowStockCount = 0;
        $totalValue    = 0;
        foreach ($products as $p) {
            if ((int)$p['qty_total'] <= (int)$p['low_threshold']) {
                $lowStockCount++;
            }
        }
        foreach ($batches as $b) {
            $totalValue += (float)$b['cost_price'] * (int)$b['qty_remaining'];
        }

        $this->view('stock.index', [
            'title'         => 'Inventory Management',
            'products'      => $products,
            'batches'       => $batches,
            'lowStockCount' => $lowStockCount,
            'totalValue'    => $totalValue
        ]);
    }
}