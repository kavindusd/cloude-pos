<?php

class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM users WHERE email = ? AND active = 1 LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM users WHERE id = ? AND active = 1 LIMIT 1');
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public static function all(): array
    {
        return static::db()->query('SELECT id, name, email, role, active, created_at FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public static function create(array $data): bool
    {
        $stmt = static::db()->prepare('
            INSERT INTO users (name, email, password, role)
            VALUES (:name, :email, :password, :role)
        ');
        return $stmt->execute([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'     => $data['role'],
        ]);
    }

    public static function toggleActive(int $id): bool
    {
        $stmt = static::db()->prepare('UPDATE users SET active = NOT active WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = static::db()->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
        return $stmt->execute([
            'name'  => $data['name'],
            'email' => $data['email'],
            'id'    => $id
        ]);
    }

    public static function updatePassword(int $id, string $plainPassword): bool
    {
        $stmt = static::db()->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([
            password_hash($plainPassword, PASSWORD_BCRYPT),
            $id
        ]);
    }
}