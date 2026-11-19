<?php

class Product extends Model
{
    protected static string $table = 'products';

    // All products with category name + stock qty
    public static function all(): array
    {
        return static::db()->query("
            SELECT p.*,
                   c.name                        AS category_name,
                   sup.name                      AS supplier_name,
                   COALESCE(s.qty_total, 0)      AS qty_total,
                   COALESCE(s.low_threshold, 5)  AS low_threshold
            FROM products p
            LEFT JOIN categories c   ON c.id = p.category_id
            LEFT JOIN suppliers  sup ON sup.id = p.supplier_id
            LEFT JOIN stock      s   ON s.product_id = p.id
            ORDER BY p.name ASC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT p.*,
                   COALESCE(s.qty_total, 0)     AS qty_total,
                   COALESCE(s.low_threshold, 5) AS low_threshold
            FROM products p
            LEFT JOIN stock s ON s.product_id = p.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(array $data): int
    {
        $db   = static::db();
        $stmt = $db->prepare("
            INSERT INTO products (category_id, supplier_id, name, barcode, buying_price, price_retail, price_wholesale, active)
            VALUES (:category_id, :supplier_id, :name, :barcode, :buying_price, :price_retail, :price_wholesale, 1)
        ");
        $stmt->execute([
            'category_id'     => $data['category_id'] ?: null,
            'supplier_id'     => $data['supplier_id'] ?: null,
            'name'            => $data['name'],
            'barcode'         => $data['barcode'] ?: null,
            'buying_price'    => $data['buying_price'] ?? 0,
            'price_retail'    => $data['price_retail'],
            'price_wholesale' => $data['price_wholesale'],
        ]);

        $productId = (int) $db->lastInsertId();

        // Create accompanying stock row
        $db->prepare("
            INSERT INTO stock (product_id, qty_total, low_threshold)
            VALUES (?, 0, ?)
        ")->execute([$productId, (int)($data['low_threshold'] ?? 5)]);

        return $productId;
    }

    public static function update(int $id, array $data): bool
    {
        $db   = static::db();
        $stmt = $db->prepare("
            UPDATE products
               SET category_id     = :category_id,
                   supplier_id     = :supplier_id,
                   name            = :name,
                   barcode         = :barcode,
                   buying_price    = :buying_price,
                   price_retail    = :price_retail,
                   price_wholesale = :price_wholesale,
                   active          = :active
             WHERE id = :id
        ");
        $ok = $stmt->execute([
            'category_id'     => $data['category_id'] ?: null,
            'supplier_id'     => $data['supplier_id'] ?: null,
            'name'            => $data['name'],
            'barcode'         => $data['barcode'] ?: null,
            'buying_price'    => $data['buying_price'] ?? 0,
            'price_retail'    => $data['price_retail'],
            'price_wholesale' => $data['price_wholesale'],
            'active'          => (int)($data['active'] ?? 1),
            'id'              => $id,
        ]);

        // Sync low-stock threshold
        $db->prepare("
            UPDATE stock SET low_threshold = ? WHERE product_id = ?
        ")->execute([(int)($data['low_threshold'] ?? 5), $id]);

        return $ok;
    }

    public static function toggleActive(int $id): bool
    {
        return static::db()
            ->prepare("UPDATE products SET active = NOT active WHERE id = ?")
            ->execute([$id]);
    }

    public static function delete(int $id): bool
    {
        return static::db()
            ->prepare("DELETE FROM products WHERE id = ?")
            ->execute([$id]);
    }

    // Dropdown use (POS, purchases)
    public static function forSelect(): array
    {
        return static::db()->query("
            SELECT id, name, buying_price, price_retail, price_wholesale
            FROM products WHERE active = 1 ORDER BY name ASC
        ")->fetchAll();
    }

    public static function barcodeExists(string $barcode, int $excludeId = 0): bool
    {
        $stmt = static::db()->prepare("
            SELECT COUNT(*) FROM products WHERE barcode = ? AND id != ?
        ");
        $stmt->execute([$barcode, $excludeId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}