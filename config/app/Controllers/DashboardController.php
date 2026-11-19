<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::require();

        if (Auth::is('cashier'))     $this->redirect('/pos');
        if (Auth::is('stock_keeper')) $this->redirect('/stock');

        $db = Database::getInstance()->getConnection();

        // Account balances
        $accounts = $db->query("SELECT * FROM accounts ORDER BY type ASC")->fetchAll();

        // Today's sales total + count
        $todaySales = $db->query("
            SELECT COUNT(*) AS count, COALESCE(SUM(total), 0) AS total
            FROM sales
            WHERE DATE(created_at) = CURDATE() AND status = 'completed'
        ")->fetch() ?: ['count' => 0, 'total' => 0];

        // Low stock count
        $lowStock = $db->query("
            SELECT COUNT(*) AS count FROM stock
            WHERE qty_total <= low_threshold
        ")->fetch() ?: ['count' => 0];

        // Unread notifications
        $notifications = Notification::unreadCount(Auth::id());

        // Pending purchases count
        $pendingPurchases = $db->query("SELECT COUNT(*) AS count FROM purchases WHERE status = 'pending'")->fetch()['count'];

        // Customer Credits (Total Outstanding)
        $customerCredits = $db->query("SELECT COALESCE(SUM(amount_remaining), 0) AS total FROM customer_credits WHERE status != 'settled'")->fetch()['total'];

        // Supplier Credits (Total Outstanding)
        $supplierCredits = $db->query("SELECT COALESCE(SUM(amount_remaining), 0) AS total FROM supplier_credits WHERE status != 'settled'")->fetch()['total'];

        // Recent 8 sales
        $recentSales = $db->query("
            SELECT s.id, s.total, s.payment_method, s.price_type, s.status, s.created_at,
                   c.name AS customer_name, u.name AS cashier_name
            FROM sales s
            LEFT JOIN customers c ON c.id = s.customer_id
            LEFT JOIN users u ON u.id = s.created_by
            ORDER BY s.created_at DESC
            LIMIT 8
        ")->fetchAll();

        // Recent 5 purchases
        $recentPurchases = $db->query("
            SELECT p.id, p.total, p.status, p.created_at, s.name AS supplier_name
            FROM purchases p
            LEFT JOIN suppliers s ON s.id = p.supplier_id
            ORDER BY p.created_at DESC
            LIMIT 5
        ")->fetchAll();

        // This month revenue
        $monthSales = $db->query("
            SELECT COALESCE(SUM(total), 0) AS total FROM sales
            WHERE MONTH(created_at) = MONTH(CURDATE())
            AND YEAR(created_at) = YEAR(CURDATE())
            AND status = 'completed'
        ")->fetch() ?: ['total' => 0];

        $this->view('dashboard.index', [
            'title'            => 'Dashboard',
            'accounts'         => $accounts ?? [],
            'todaySales'       => $todaySales,
            'lowStock'         => $lowStock,
            'notifications'    => $notifications ?? 0,
            'recentSales'      => $recentSales ?? [],
            'recentPurchases'  => $recentPurchases ?? [],
            'monthSales'       => $monthSales,
            'pendingPurchases' => $pendingPurchases,
            'customerCredits'  => $customerCredits,
            'supplierCredits'  => $supplierCredits,
        ]);
    }
}