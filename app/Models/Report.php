<?php

class Report extends Model
{
    public static function dailySales(string $date): array
    {
        $stmt = static::db()->prepare("
            SELECT 
                COUNT(*) AS total_orders,
                COALESCE(SUM(subtotal), 0) AS subtotal,
                COALESCE(SUM(discount), 0) AS discount,
                COALESCE(SUM(total), 0)    AS total
            FROM sales
            WHERE DATE(created_at) = ? AND status = 'completed'
        ");
        $stmt->execute([$date]);
        return $stmt->fetch();
    }

    public static function dailyProfit(string $date): float
    {
        // Profit = SUM(sale_item.subtotal - (sale_item.qty * batch.cost_price))
        $stmt = static::db()->prepare("
            SELECT SUM(si.subtotal - (si.qty * b.cost_price))
            FROM sale_items si
            JOIN sales s   ON s.id = si.sale_id
            JOIN batches b ON b.id = si.batch_id
            WHERE DATE(s.created_at) = ? AND s.status = 'completed'
        ");
        $stmt->execute([$date]);
        return (float) $stmt->fetchColumn();
    }

    public static function dailyPurchases(string $date): array
    {
        $stmt = static::db()->prepare("
            SELECT 
                COUNT(*) AS total_orders,
                COALESCE(SUM(total), 0) AS total
            FROM purchases
            WHERE DATE(created_at) = ?
        ");
        $stmt->execute([$date]);
        return $stmt->fetch();
    }

    public static function topProducts(string $date, int $limit = 5): array
    {
        $stmt = static::db()->prepare("
            SELECT p.name, SUM(si.qty) AS total_qty, SUM(si.subtotal) AS total_revenue
            FROM sale_items si
            JOIN sales s    ON s.id = si.sale_id
            JOIN products p ON p.id = si.product_id
            WHERE DATE(s.created_at) = ? AND s.status = 'completed'
            GROUP BY p.id
            ORDER BY total_qty DESC
            LIMIT ?
        ");
        $stmt->execute([$date, $limit]);
        return $stmt->fetchAll();
    }

    public static function categorySales(string $date): array
    {
        $stmt = static::db()->prepare("
            SELECT c.name, SUM(si.subtotal) AS total_revenue
            FROM sale_items si
            JOIN sales s      ON s.id = si.sale_id
            JOIN products p   ON p.id = si.product_id
            JOIN categories c ON c.id = p.category_id
            WHERE DATE(s.created_at) = ? AND s.status = 'completed'
            GROUP BY c.id
            ORDER BY total_revenue DESC
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public static function transactionSummary(string $date): array
    {
        $stmt = static::db()->prepare("
            SELECT direction, SUM(amount) AS total
            FROM account_transactions
            WHERE DATE(created_at) = ?
            GROUP BY direction
        ");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
}
