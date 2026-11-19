<?php

class Supplier extends Model
{
    protected static string $table = 'suppliers';

    public static function all(): array
    {
        return static::db()->query("
            SELECT * FROM suppliers ORDER BY name ASC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("SELECT * FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $stmt = static::db()->prepare("
            INSERT INTO suppliers (name, phone, email, address)
            VALUES (:name, :phone, :email, :address)
        ");
        $stmt->execute([
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?: null,
            'email'   => $data['email'] ?: null,
            'address' => $data['address'] ?: null,
        ]);
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = static::db()->prepare("
            UPDATE suppliers
               SET name    = :name,
                   phone   = :phone,
                   email   = :email,
                   address = :address
             WHERE id = :id
        ");
        return $stmt->execute([
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?: null,
            'email'   => $data['email'] ?: null,
            'address' => $data['address'] ?: null,
            'id'      => $id,
        ]);
    }

    public static function toggleActive(int $id): bool
    {
        return static::db()
            ->prepare("UPDATE suppliers SET active = NOT active WHERE id = ?")
            ->execute([$id]);
    }

    // For purchase form dropdowns
    public static function forSelect(): array
    {
        return static::db()->query("
            SELECT id, name FROM suppliers WHERE active = 1 ORDER BY name ASC
        ")->fetchAll();
    }
}