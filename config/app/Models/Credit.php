<?php

class Credit extends Model
{
    protected static string $table = 'supplier_credits';

    // ── Supplier Credits ──────────────────────────────────────────────────────

    public static function supplierCredits(): array
    {
        return static::db()->query("
            SELECT sc.*,
                   s.name  AS supplier_name,
                   p.id    AS purchase_ref
            FROM supplier_credits sc
            LEFT JOIN suppliers s ON s.id = sc.supplier_id
            LEFT JOIN purchases p ON p.id = sc.purchase_id
            ORDER BY sc.created_at DESC
        ")->fetchAll();
    }

    public static function supplierCreditById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT sc.*, s.name AS supplier_name
            FROM supplier_credits sc
            LEFT JOIN suppliers s ON s.id = sc.supplier_id
            WHERE sc.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function settleSupplier(int $creditId, float $amount, int $accountId, int $userId, string $note = '', string $date = null): bool
    {
        $db     = static::db();
        $credit = self::supplierCreditById($creditId);

        if (!$credit || $credit['status'] === 'paid') return false;

        $amount    = min($amount, (float)$credit['amount_remaining']);
        $remaining = (float)$credit['amount_remaining'] - $amount;
        $status    = $remaining <= 0 ? 'paid' : 'partial';

        $db->beginTransaction();
        try {
            // Update credit record
            $db->prepare("
                UPDATE supplier_credits
                   SET amount_remaining = ?, status = ?
                 WHERE id = ?
            ")->execute([$remaining, $status, $creditId]);

            // Debit account (if not external)
            if ($accountId > 0) {
                $db->prepare("
                    UPDATE accounts SET balance = balance - ? WHERE id = ?
                ")->execute([$amount, $accountId]);
            }

            // Transaction log
            $finalNote = !empty($note) ? $note : "Supplier credit settlement #{$creditId}";
            if ($accountId === 0) $finalNote .= " (External Payment)";

            $db->prepare("
                INSERT INTO account_transactions
                    (account_id, direction, amount, reference_type, reference_id, note, created_by, created_at)
                VALUES (?, 'out', ?, 'supplier_credit', ?, ?, ?, ?)
            ")->execute([
                $accountId > 0 ? $accountId : null, 
                $amount, 
                $creditId,
                $finalNote, 
                $userId,
                $date ?: date('Y-m-d H:i:s'),
            ]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // ── Customer Credits ──────────────────────────────────────────────────────

    public static function customerCredits(): array
    {
        return static::db()->query("
            SELECT cc.*,
                   c.name AS customer_name,
                   s.id   AS sale_ref
            FROM customer_credits cc
            LEFT JOIN customers c ON c.id = cc.customer_id
            LEFT JOIN sales     s ON s.id = cc.sale_id
            ORDER BY cc.created_at DESC
        ")->fetchAll();
    }

    public static function customerCreditById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT cc.*, c.name AS customer_name
            FROM customer_credits cc
            LEFT JOIN customers c ON c.id = cc.customer_id
            WHERE cc.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function settleCustomer(int $creditId, float $amount, int $accountId, int $userId, string $note = '', string $date = null): bool
    {
        $db     = static::db();
        $credit = self::customerCreditById($creditId);

        if (!$credit || $credit['status'] === 'paid') return false;

        $amount    = min($amount, (float)$credit['amount_remaining']);
        $remaining = (float)$credit['amount_remaining'] - $amount;
        $status    = $remaining <= 0 ? 'paid' : 'partial';

        $db->beginTransaction();
        try {
            // Update credit record
            $db->prepare("
                UPDATE customer_credits
                   SET amount_remaining = ?, status = ?
                 WHERE id = ?
            ")->execute([$remaining, $status, $creditId]);

            // Credit account (money coming in)
            $db->prepare("
                UPDATE accounts SET balance = balance + ? WHERE id = ?
            ")->execute([$amount, $accountId]);

            // Transaction log
            $finalNote = !empty($note) ? $note : "Customer credit settlement #{$creditId}";

            $db->prepare("
                INSERT INTO account_transactions
                    (account_id, direction, amount, reference_type, reference_id, note, created_by, created_at)
                VALUES (?, 'in', ?, 'customer_credit', ?, ?, ?, ?)
            ")->execute([
                $accountId, $amount, $creditId,
                $finalNote, $userId,
                $date ?: date('Y-m-d H:i:s'),
            ]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    // ── Summary helpers ───────────────────────────────────────────────────────

    public static function totalSupplierOutstanding(): float
    {
        return (float) static::db()->query("
            SELECT COALESCE(SUM(amount_remaining), 0)
            FROM supplier_credits WHERE status != 'paid'
        ")->fetchColumn();
    }

    public static function totalCustomerOutstanding(): float
    {
        return (float) static::db()->query("
            SELECT COALESCE(SUM(amount_remaining), 0)
            FROM customer_credits WHERE status != 'paid'
        ")->fetchColumn();
    }
}