<?php

class TransactionController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('transactions');

        $startDate = $this->input('start_date', date('Y-m-d', strtotime('-30 days')));
        $endDate   = $this->input('end_date', date('Y-m-d'));
        $accountId = $this->input('account_id', '');

        $db = Database::getInstance()->getConnection();
        
        // Fetch all accounts for filter
        $accounts = $db->query("SELECT * FROM accounts ORDER BY name ASC")->fetchAll();

        // Fetch transactions grouped by date
        $query = "
            SELECT t.*, a.name AS account_name, u.name AS user_name
            FROM account_transactions t
            LEFT JOIN accounts a ON a.id = t.account_id
            LEFT JOIN users    u ON u.id = t.created_by
            WHERE DATE(t.created_at) BETWEEN ? AND ?
        ";
        $params = [$startDate, $endDate];

        if ($accountId !== '') {
            $query .= " AND t.account_id = ?";
            $params[] = $accountId;
        }

        $query .= " ORDER BY t.created_at DESC";

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll();

        // Group by day
        $grouped = [];
        foreach ($transactions as $t) {
            $day = date('Y-m-d', strtotime($t['created_at']));
            $grouped[$day][] = $t;
        }

        $this->view('transactions.index', [
            'title'     => 'All Transactions',
            'grouped'   => $grouped,
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'accounts'  => $accounts,
            'accountId' => $accountId
        ]);
    }
}
