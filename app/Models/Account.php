<?php

class Account extends Model
{
    protected static string $table = 'accounts';

    public static function all(): array
    {
        return static::db()->query("SELECT * FROM accounts ORDER BY type ASC")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateBalance(int $id, float $newBalance, int $updatedBy, string $note = ''): void
    {
        $account = static::findById($id);
        if (!$account) return;

        $diff      = $newBalance - (float)$account['balance'];
        $direction = $diff >= 0 ? 'in' : 'out';
        $amount    = abs($diff);

        // Update balance
        $stmt = static::db()->prepare("UPDATE accounts SET balance = ? WHERE id = ?");
        $stmt->execute([$newBalance, $id]);

        // Log the adjustment
        if ($amount > 0) {
            $stmt = static::db()->prepare("
                INSERT INTO account_transactions
                    (account_id, direction, amount, reference_type, note, created_by)
                VALUES (?, ?, ?, 'adjustment', ?, ?)
            ");
            $stmt->execute([$id, $direction, $amount, $note ?: 'Balance adjustment', $updatedBy]);
        }
    }

    public static function transactions(int $accountId, int $limit = 30): array
    {
        $stmt = static::db()->prepare("
            SELECT t.*, u.name AS created_by_name
            FROM account_transactions t
            LEFT JOIN users u ON u.id = t.created_by
            WHERE t.account_id = ?
            ORDER BY t.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$accountId, $limit]);
        return $stmt->fetchAll();
    }

    public static function credit(int $accountId, float $amount, string $refType, int $refId, int $userId, string $note = ''): void
    {
        static::db()->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?")->execute([$amount, $accountId]);
        static::logTransaction($accountId, 'in', $amount, $refType, $refId, $userId, $note);
    }

    public static function debit(int $accountId, float $amount, string $refType, int $refId, int $userId, string $note = ''): void
    {
        static::db()->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?")->execute([$amount, $accountId]);
        static::logTransaction($accountId, 'out', $amount, $refType, $refId, $userId, $note);
    }

    private static function logTransaction(int $accountId, string $direction, float $amount, string $refType, int $refId, int $userId, string $note): void
    {
        $stmt = static::db()->prepare("
            INSERT INTO account_transactions
                (account_id, direction, amount, reference_type, reference_id, note, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$accountId, $direction, $amount, $refType, $refId, $userId, $note]);
    }
}