<?php

define('BASE_PATH', __DIR__);

// Simple .env loader
if (file_exists(BASE_PATH . '/.env')) {
    $lines = file(BASE_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$config = require BASE_PATH . '/config/database.php';

try {
    echo "Connecting to MySQL at {$config['host']}:{$config['port']}...\n";
    
    // 1. Connect without database name to create it if it doesn't exist
    $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 2. Create Database
    $dbName = $config['database'];
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbName' checked/created successfully.\n";

    // 3. Select Database
    $pdo->exec("USE `$dbName` ");
    echo "Switched to database '$dbName'.\n";

    // 3.1 Drop existing tables to ensure a fresh start
    echo "Cleaning up existing tables...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE `$table` ");
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "Database cleaned.\n";

    // Helper to execute multi-statement SQL files
    function executeSqlFile($pdo, $path) {
        if (!file_exists($path)) {
            echo "Warning: File not found at $path\n";
            return;
        }
        
        echo "Executing " . basename($path) . "...\n";
        $sql = file_get_contents($path);
        
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        
        // Split by semicolon, but try to avoid splitting inside quotes (simple version)
        // For standard schema/seed files, simple split usually works if no complex triggers/procs
        $statements = explode(';', $sql);
        $count = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $pdo->exec($statement);
                $count++;
            }
        }
        echo "Successfully executed $count statements from " . basename($path) . ".\n";
    }

    // 4. Run Schema
    executeSqlFile($pdo, BASE_PATH . '/database/schema.sql');

    // 5. Run Seed
    executeSqlFile($pdo, BASE_PATH . '/database/seed.sql');

    echo "\nMigration completed successfully!\n";

} catch (PDOException $e) {
    die("\nMigration failed: " . $e->getMessage() . "\n");
}
