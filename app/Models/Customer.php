<?php

class Customer extends Model
{
    protected static string $table = 'customers';

    public static function all(): array
    {
        return static::db()->query("
            SELECT c.*,
                   COALESCE(SUM(cc.amount_remaining), 0) AS outstanding_credit
            FROM customers c
            LEFT JOIN customer_credits cc ON cc.customer_id = c.id AND cc.status != 'paid'
            GROUP BY c.id
            ORDER BY c.name ASC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT c.*,
                   COALESCE(SUM(cc.amount_remaining), 0) AS outstanding_credit
            FROM customers c
            LEFT JOIN customer_credits cc ON cc.customer_id = c.id AND cc.status != 'paid'
            WHERE c.id = ?
            GROUP BY c.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = static::db()->prepare("
            INSERT INTO customers (name, phone, email, address, type, credit_limit)
            VALUES (:name, :phone, :email, :address, :type, :credit_limit)
        ");
        $stmt->execute([
            'name'         => $data['name'],
            'phone'        => $data['phone']  ?: null,
            'email'        => $data['email']  ?: null,
            'address'      => $data['address'] ?: null,
            'type'         => $data['type'],
            'credit_limit' => (float)$data['credit_limit'],
        ]);
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = static::db()->prepare("
            UPDATE customers
               SET name         = :name,
                   phone        = :phone,
                   email        = :email,
                   address      = :address,
                   type         = :type,
                   credit_limit = :credit_limit
             WHERE id = :id
        ");
        return $stmt->execute([
            'name'         => $data['name'],
            'phone'        => $data['phone']  ?: null,
            'email'        => $data['email']  ?: null,
            'address'      => $data['address'] ?: null,
            'type'         => $data['type'],
            'credit_limit' => (float)$data['credit_limit'],
            'id'           => $id,
        ]);
    }

    public static function toggleActive(int $id): bool
    {
        return static::db()
            ->prepare("UPDATE customers SET active = NOT active WHERE id = ?")
            ->execute([$id]);
    }

    // For POS / sale form dropdowns
    public static function forSelect(): array
    {
        return static::db()->query("
            SELECT id, name, type, credit_limit FROM customers
            WHERE active = 1 ORDER BY name ASC
        ")->fetchAll();
    }

    public static function availableCredit(int $id): float
    {
        $stmt = static::db()->prepare("
            SELECT c.credit_limit - COALESCE(SUM(cc.amount_remaining), 0)
            FROM customers c
            LEFT JOIN customer_credits cc ON cc.customer_id = c.id AND cc.status != 'paid'
            WHERE c.id = ?
            GROUP BY c.id
        ");
        $stmt->execute([$id]);
        return (float)($stmt->fetchColumn() ?? 0);
    }
}