<?php

class Sale extends Model
{
    protected static string $table = 'sales';

    public static function all(): array
    {
        return static::db()->query("
            SELECT s.*,
                   c.name AS customer_name,
                   u.name AS created_by_name,
                   COUNT(si.id) AS item_count
            FROM sales s
            LEFT JOIN customers c  ON c.id = s.customer_id
            LEFT JOIN users     u  ON u.id = s.created_by
            LEFT JOIN sale_items si ON si.sale_id = s.id
            GROUP BY s.id
            ORDER BY s.created_at DESC
            LIMIT 200
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("
            SELECT s.*,
                   c.name AS customer_name,
                   u.name AS created_by_name
            FROM sales s
            LEFT JOIN customers c ON c.id = s.customer_id
            LEFT JOIN users     u ON u.id = s.created_by
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function itemsFor(int $saleId): array
    {
        $stmt = static::db()->prepare("
            SELECT si.*, p.name AS product_name
            FROM sale_items si
            LEFT JOIN products p ON p.id = si.product_id
            WHERE si.sale_id = ?
        ");
        $stmt->execute([$saleId]);
        return $stmt->fetchAll();
    }

    public static function todayTotal(): float
    {
        $stmt = static::db()->query("
            SELECT COALESCE(SUM(total), 0)
            FROM sales
            WHERE DATE(created_at) = CURDATE() AND status != 'voided'
        ");
        return (float) $stmt->fetchColumn();
    }

    public static function monthTotal(): float
    {
        $stmt = static::db()->query("
            SELECT COALESCE(SUM(total), 0)
            FROM sales
            WHERE MONTH(created_at) = MONTH(CURDATE())
              AND YEAR(created_at)  = YEAR(CURDATE())
              AND status != 'voided'
        ");
        return (float) $stmt->fetchColumn();
    }

    public static function recent(int $limit = 8): array
    {
        $stmt = static::db()->prepare("
            SELECT s.*,
                   c.name AS customer_name,
                   COUNT(si.id) AS item_count
            FROM sales s
            LEFT JOIN customers  c  ON c.id  = s.customer_id
            LEFT JOIN sale_items si ON si.sale_id = s.id
            GROUP BY s.id
            ORDER BY s.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Complete a sale inside a DB transaction.
     * Handles: sale record, sale_items, stock deduction,
     *          account credit, customer credit if needed.
     */
    public static function create(array $header, array $items): int
    {
        $db = static::db();
        $db->beginTransaction();

        try {
            $subtotal  = array_sum(array_map(fn($i) => $i['subtotal'], $items));
            $discount  = (float)($header['discount'] ?? 0);
            $total     = max(0, $subtotal - $discount);
            $status    = $header['payment_method'] === 'credit' ? 'credit' : 'completed';

            // 1. Sale header
            $stmt = $db->prepare("
                INSERT INTO sales
                    (customer_id, account_id, payment_method,
                     subtotal, discount, total, status, note, created_by)
                VALUES
                    (:customer_id, :account_id, :payment_method,
                     :subtotal, :discount, :total, :status, :note, :created_by)
            ");
            $stmt->execute([
                'customer_id'    => $header['customer_id'] ?: null,
                'account_id'     => $header['account_id']  ?: null,
                'payment_method' => $header['payment_method'],
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'status'         => $status,
                'note'           => $header['note'] ?: null,
                'created_by'     => $header['created_by'],
            ]);
            $saleId = (int) $db->lastInsertId();

            // 2. Line items + stock deduction (FIFO from batches)
            foreach ($items as $item) {
                $qtyToDeduct = (int)$item['qty'];
                $productId   = (int)$item['product_id'];

                // Find available batches for this product (FIFO: oldest first)
                $stmt = $db->prepare("
                    SELECT id, qty_remaining 
                    FROM batches 
                    WHERE product_id = ? AND qty_remaining > 0 
                    ORDER BY purchase_date ASC, id ASC
                ");
                $stmt->execute([$productId]);
                $batches = $stmt->fetchAll();

                foreach ($batches as $batch) {
                    if ($qtyToDeduct <= 0) break;

                    $take = min($qtyToDeduct, (int)$batch['qty_remaining']);
                    
                    // Deduct from batch
                    $db->prepare("UPDATE batches SET qty_remaining = qty_remaining - ? WHERE id = ?")
                       ->execute([$take, $batch['id']]);

                    // Record sale item for this batch
                    // Note: If one line item spans multiple batches, we record multiple sale_items rows
                    // to keep the batch_id reference accurate.
                    $db->prepare("
                        INSERT INTO sale_items
                            (sale_id, product_id, batch_id, qty, unit_price, subtotal)
                        VALUES (?, ?, ?, ?, ?, ?)
                    ")->execute([
                        $saleId,
                        $productId,
                        $batch['id'],
                        $take,
                        $item['unit_price'],
                        $take * $item['unit_price'],
                    ]);

                    $qtyToDeduct -= $take;
                }

                // If after checking all batches we still have qtyToDeduct > 0, 
                // it means we oversold (stock went negative). 
                // In this case, we still record the remaining as a non-batch item or handle as error.
                // For now, we'll allow it but without a batch_id.
                if ($qtyToDeduct > 0) {
                    $db->prepare("
                        INSERT INTO sale_items
                            (sale_id, product_id, batch_id, qty, unit_price, subtotal)
                        VALUES (?, ?, NULL, ?, ?, ?)
                    ")->execute([
                        $saleId,
                        $productId,
                        $qtyToDeduct,
                        $item['unit_price'],
                        $qtyToDeduct * $item['unit_price'],
                    ]);
                }

                // Update master stock total
                $db->prepare("
                    UPDATE stock SET qty_total = qty_total - ? WHERE product_id = ?
                ")->execute([$item['qty'], $productId]);
            }

            // 3. Account credit (cash / bank)
            if ($header['payment_method'] !== 'credit' && $header['account_id']) {
                $db->prepare("
                    UPDATE accounts SET balance = balance + ? WHERE id = ?
                ")->execute([$total, $header['account_id']]);

                $db->prepare("
                    INSERT INTO account_transactions
                        (account_id, direction, amount, reference_type, reference_id, note, created_by)
                    VALUES (?, 'in', ?, 'sale', ?, ?, ?)
                ")->execute([
                    $header['account_id'],
                    $total,
                    $saleId,
                    "Sale #{$saleId}",
                    $header['created_by'],
                ]);
            }

            // 4. Customer credit record
            if ($header['payment_method'] === 'credit' && $header['customer_id']) {
                $db->prepare("
                    INSERT INTO customer_credits
                        (customer_id, sale_id, original_amount, amount_remaining, status)
                    VALUES (?, ?, ?, ?, 'unpaid')
                ")->execute([
                    $header['customer_id'],
                    $saleId,
                    $total,
                    $total,
                ]);
            }

            $db->commit();
            return $saleId;

        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}