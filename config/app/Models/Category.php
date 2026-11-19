<?php

class Category extends Model
{
    protected static string $table = 'categories';

    public static function all(): array
    {
        return static::db()->query("
            SELECT c.*, COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.name ASC
        ")->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function create(string $name, string $description = ''): bool
    {
        $stmt = static::db()->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    public static function update(int $id, string $name, string $description = ''): bool
    {
        $stmt = static::db()->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = static::db()->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function forSelect(): array
    {
        return static::db()->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll();
    }
}