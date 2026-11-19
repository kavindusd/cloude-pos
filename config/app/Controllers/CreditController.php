<?php

class CreditController extends Controller
{
    // ── Supplier credits ──────────────────────────────────────────────────────

    public function suppliers(): void
    {
        Auth::requirePermission('credits');
        $this->view('credits.suppliers', [
            'title'    => 'Supplier Credits',
            'credits'  => Credit::supplierCredits(),
            'accounts' => Account::all(),
            'totalOwed' => Credit::totalSupplierOutstanding(),
        ]);
    }

    public function settleSupplier(int $id): void
    {
        Auth::requirePermission('credits');

        $credit = Credit::supplierCreditById($id);
        if (!$credit) {
            Session::flash('error', 'Credit record not found.');
            $this->redirect('/credits/suppliers');
            return;
        }

        if ($credit['status'] === 'paid') {
            Session::flash('error', 'This credit is already fully paid.');
            $this->redirect('/credits/suppliers');
            return;
        }

        $amount    = (float)$this->input('amount', 0);
        $accountId = (int)$this->input('account_id', 0);
        $note      = trim($this->input('note', ''));
        $date      = $this->input('date', date('Y-m-d'));

        if ($amount <= 0) {
            Session::flash('error', 'Settlement amount must be greater than zero.');
            $this->redirect('/credits/suppliers');
            return;
        }

        // Note: For suppliers, accountId 0 is "External"
        if ($accountId < 0) {
            Session::flash('error', 'Please select a valid account or choose External.');
            $this->redirect('/credits/suppliers');
            return;
        }

        if ($amount > (float)$credit['amount_remaining']) {
            Session::flash('error', 'Amount exceeds outstanding balance.');
            $this->redirect('/credits/suppliers');
            return;
        }

        Credit::settleSupplier($id, $amount, $accountId, Auth::id(), $note, $date);
        ActivityLog::record(Auth::id(), 'supplier_credit_settled', 'supplier_credit', $id,
            "LKR " . number_format($amount, 2));
        Session::flash('success', 'Supplier credit settlement of LKR ' . number_format($amount, 2) . ' recorded.');
        $this->redirect('/credits/suppliers');
    }

    // ── Customer credits ──────────────────────────────────────────────────────

    public function customers(): void
    {
        Auth::requirePermission('credits');
        $this->view('credits.customers', [
            'title'       => 'Customer Credits',
            'credits'     => Credit::customerCredits(),
            'accounts'    => Account::all(),
            'totalOwed'   => Credit::totalCustomerOutstanding(),
        ]);
    }

    public function settleCustomer(int $id): void
    {
        Auth::requirePermission('credits');

        $credit = Credit::customerCreditById($id);
        if (!$credit) {
            Session::flash('error', 'Credit record not found.');
            $this->redirect('/credits/customers');
            return;
        }

        if ($credit['status'] === 'paid') {
            Session::flash('error', 'This credit is already fully paid.');
            $this->redirect('/credits/customers');
            return;
        }

        $amount    = (float)$this->input('amount', 0);
        $accountId = (int)$this->input('account_id', 0);
        $note      = trim($this->input('note', ''));

        if ($amount <= 0) {
            Session::flash('error', 'Settlement amount must be greater than zero.');
            $this->redirect('/credits/customers');
            return;
        }

        if (!$accountId) {
            Session::flash('error', 'Please select an account.');
            $this->redirect('/credits/customers');
            return;
        }

        if ($amount > (float)$credit['amount_remaining']) {
            Session::flash('error', 'Amount exceeds outstanding balance.');
            $this->redirect('/credits/customers');
            return;
        }

        Credit::settleCustomer($id, $amount, $accountId, Auth::id(), $note, $date);
        ActivityLog::record(Auth::id(), 'customer_credit_settled', 'customer_credit', $id,
            "LKR " . number_format($amount, 2));
        Session::flash('success', 'Customer credit settlement of LKR ' . number_format($amount, 2) . ' recorded.');
        $this->redirect('/credits/customers');
    }
}