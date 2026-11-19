<?php

class AccountController extends Controller
{
    public function index(): void
    {
        Auth::requirePermission('accounts');

        $accounts = Account::all();

        // Get recent transactions for each account
        $transactions = [];
        foreach ($accounts as $acc) {
            $transactions[$acc['id']] = Account::transactions((int)$acc['id'], 20);
        }

        $this->view('accounts.index', [
            'title'        => 'Accounts',
            'accounts'     => $accounts,
            'transactions' => $transactions,
        ]);
    }

    public function update(): void
    {
        Auth::requirePermission('accounts');

        $id         = (int)$this->input('account_id');
        $newBalance = (float)$this->input('balance');
        $note       = trim($this->input('note', 'Balance adjustment'));

        if (!$id || $newBalance < 0) {
            Session::flash('error', 'Invalid input.');
            $this->redirect('/accounts');
            return;
        }

        Account::updateBalance($id, $newBalance, Auth::id(), $note);
        ActivityLog::record(Auth::id(), 'account_updated', 'account', $id, "Balance set to {$newBalance}");

        Session::flash('success', 'Account balance updated.');
        $this->redirect('/accounts');
    }
}