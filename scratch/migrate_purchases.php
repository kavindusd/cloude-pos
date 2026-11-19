<?php
define('BASE_PATH', dirname(__DIR__));

// .env loader
if (file_exists(BASE_PATH . '/.env')) {
    $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

require BASE_PATH . '/core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    // Update status column
    $db->exec("ALTER TABLE purchases MODIFY COLUMN status ENUM('pending', 'received', 'cancelled') NOT NULL DEFAULT 'pending'");
    
    // Convert existing 'paid' and 'credit' to 'received'
    $db->exec("UPDATE purchases SET status = 'received'");
    
    echo "Successfully updated purchases table status.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
