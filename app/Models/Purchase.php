<?php

class Purchase extends Model
{
    protected static string $table = 'purchases';

    public static function all(): array
    {
        return static::db()->query("
            SELECT p.*,
                   s.name  AS supplier_name,
                   u.name  AS created_by_name,
                   COUNT(pi.id) AS item_count
            FROM purchases p
            LEFT JOIN suppliers     s  ON s.id  = p.supplier_id
            LEFT JOIN users         u  ON u.id  = p.created_by
            LEFT JOIN purchase_items pi ON pi.purchase_id = p.id
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT p.*,
                   s.name AS supplier_name,
                   u.name AS created_by_name
            FROM purchases p
            LEFT JOIN suppliers s ON s.id = p.supplier_id
            LEFT JOIN users     u ON u.id = p.created_by
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function itemsFor(int $purchaseId): array
    {
        $stmt = static::db()->prepare("
            SELECT pi.*, pr.name AS product_name, b.batch_number
            FROM purchase_items pi
            LEFT JOIN products pr ON pr.id = pi.product_id
            LEFT JOIN batches  b  ON b.id  = pi.batch_id
            WHERE pi.purchase_id = ?
        ");
        $stmt->execute([$purchaseId]);
        return $stmt->fetchAll();
    }

    /**
     * Create a purchase request (Pending).
     * Only records the header and items. Stock is NOT updated yet.
     */
    public static function create(array $header, array $items): int
    {
        $db = static::db();
        $db->beginTransaction();

        try {
            $total = array_sum(array_map(fn($i) => $i['subtotal'], $items));

            // 1. Insert purchase header as PENDING
            $stmt = $db->prepare("
                INSERT INTO purchases
                    (supplier_id, account_id, payment_method, total, status, note, created_by)
                VALUES
                    (:supplier_id, :account_id, :payment_method, :total, 'pending', :note, :created_by)
            ");
            $stmt->execute([
                'supplier_id'    => $header['supplier_id'],
                'account_id'     => $header['account_id'] ?: null,
                'payment_method' => $header['payment_method'],
                'total'          => $total,
                'note'           => $header['note'] ?: null,
                'created_by'     => $header['created_by'],
            ]);
            $purchaseId = (int) $db->lastInsertId();

            // 2. Line items (without batch_id yet)
            foreach ($items as $item) {
                $db->prepare("
                    INSERT INTO purchase_items
                        (purchase_id, product_id, qty, unit_cost, subtotal)
                    VALUES (?, ?, ?, ?, ?)
                ")->execute([
                    $purchaseId,
                    $item['product_id'],
                    $item['qty'],
                    $item['unit_cost'],
                    $item['subtotal'],
                ]);
            }

            $db->commit();
            return $purchaseId;

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Mark a pending purchase as RECEIVED.
     * This updates stock, creates batches, and handles financial logic.
     */
    public static function receive(int $id, int $userId): bool
    {
        $db = static::db();
        $purchase = self::findById($id);
        if (!$purchase || $purchase['status'] !== 'pending') return false;

        $items = self::itemsFor($id);
        $db->beginTransaction();

        try {
            // 1. Update purchase status
            $db->prepare("UPDATE purchases SET status = 'received' WHERE id = ?")->execute([$id]);

            // 2. Process items → batches + stock
            foreach ($items as $item) {
                $batchNo = 'BCH-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

                // Batch
                $db->prepare("
                    INSERT INTO batches
                        (product_id, purchase_id, batch_number, qty_received, qty_remaining, cost_price, purchase_date)
                    VALUES (?, ?, ?, ?, ?, ?, CURDATE())
                ")->execute([
                    $item['product_id'],
                    $id,
                    $batchNo,
                    $item['qty'],
                    $item['qty'],
                    $item['unit_cost'],
                ]);
                $batchId = (int) $db->lastInsertId();

                // Link item to batch
                $db->prepare("UPDATE purchase_items SET batch_id = ? WHERE id = ?")
                   ->execute([$batchId, $item['id']]);

                // Stock update
                $db->prepare("UPDATE stock SET qty_total = qty_total + ? WHERE product_id = ?")
                   ->execute([$item['qty'], $item['product_id']]);
            }

            // 3. Financial logic
            $total = (float)$purchase['total'];
            if ($purchase['payment_method'] !== 'credit' && $purchase['account_id']) {
                // Account debit
                $db->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?")
                   ->execute([$total, $purchase['account_id']]);

                // Transaction log
                $db->prepare("
                    INSERT INTO account_transactions
                        (account_id, direction, amount, reference_type, reference_id, note, created_by)
                    VALUES (?, 'out', ?, 'purchase', ?, ?, ?)
                ")->execute([
                    $purchase['account_id'],
                    $total,
                    $id,
                    "Purchase #{$id} Received",
                    $userId,
                ]);
            } elseif ($purchase['payment_method'] === 'credit') {
                // Supplier credit
                $db->prepare("
                    INSERT INTO supplier_credits
                        (supplier_id, purchase_id, original_amount, amount_remaining, status)
                    VALUES (?, ?, ?, ?, 'unpaid')
                ")->execute([
                    $purchase['supplier_id'],
                    $id,
                    $total,
                    $total,
                ]);
            }

            $db->commit();
            return true;

        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function cancel(int $id): bool
    {
        $db = static::db();
        $purchase = self::findById($id);
        if (!$purchase || $purchase['status'] !== 'pending') return false;

        return $db->prepare("UPDATE purchases SET status = 'cancelled' WHERE id = ?")->execute([$id]);
    }
}