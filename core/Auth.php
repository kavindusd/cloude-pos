<?php

class Auth
{
    // Permission map — what each role can access
    private static array $permissions = [
        'super_admin' => [
            'dashboard', 'users', 'accounts', 'suppliers', 'purchases',
            'products', 'categories', 'stock', 'sales', 'pos', 'transactions',
            'reports', 'customers', 'notifications', 'settings', 'credits',
        ],
        'admin' => [
            'dashboard', 'accounts', 'suppliers', 'purchases', 'products', 'categories',
            'stock', 'sales', 'pos', 'transactions', 'reports', 'customers', 'notifications', 'credits',
        ],
        'manager' => [
            'dashboard', 'products', 'categories', 'stock',
            'sales', 'pos', 'reports', 'customers', 'notifications',
        ],
        'stock_keeper' => [
            'dashboard', 'products', 'categories', 'stock', 'notifications',
        ],
        'cashier' => [
            'pos', 'sales',
        ],
    ];

    // Where each role lands after login
    private static array $redirects = [
        'super_admin'  => '/dashboard',
        'admin'        => '/dashboard',
        'manager'      => '/dashboard',
        'stock_keeper' => '/stock',
        'cashier'      => '/pos',
    ];

    public static function check(): bool
    {
        return Session::has('user');
    }

    public static function user(): ?array
    {
        return Session::get('user');
    }

    public static function role(): ?string
    {
        return Session::get('user')['role'] ?? null;
    }

    public static function id(): ?int
    {
        return Session::get('user')['id'] ?? null;
    }

    public static function is(string $role): bool
    {
        return static::role() === $role;
    }

    public static function can(string $permission): bool
    {
        $role = static::role();
        if (!$role) return false;
        return in_array($permission, static::$permissions[$role] ?? []);
    }

    public static function login(array $user): void
    {
        // Strip password before storing in session
        unset($user['password']);
        Session::set('user', $user);
    }

    public static function logout(): void
    {
        Session::forget('user');
    }

    public static function homeRoute(): string
    {
        return static::$redirects[static::role()] ?? '/login';
    }

    // Middleware — redirect if not logged in
    public static function require(): void
    {
        if (!static::check()) {
            header('Location: /login');
            exit;
        }
    }

    // Middleware — redirect if no permission
    public static function requirePermission(string $permission): void
    {
        static::require();
        if (!static::can($permission)) {
            http_response_code(403);
            require BASE_PATH . '/app/Views/errors/403.php';
            exit;
        }
    }

    // Middleware — super admin only
    public static function requireSuperAdmin(): void
    {
        static::require();
        if (!static::is('super_admin')) {
            http_response_code(403);
            require BASE_PATH . '/app/Views/errors/403.php';
            exit;
        }
    }
}