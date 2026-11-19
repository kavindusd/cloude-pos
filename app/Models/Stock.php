<?php

class Stock extends Model
{
    protected static string $table = 'stock';

    public static function batches(): array
    {
        return static::db()->query("
            SELECT b.*, p.name AS product_name, p.barcode, pur.supplier_id, s.name AS supplier_name
            FROM batches b
            LEFT JOIN products  p   ON p.id = b.product_id
            LEFT JOIN purchases pur ON pur.id = b.purchase_id
            LEFT JOIN suppliers s   ON s.id = pur.supplier_id
            WHERE b.qty_remaining > 0
            ORDER BY b.purchase_date DESC
        ")->fetchAll();
    }
}
