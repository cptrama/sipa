<?php
/**
 * Database Connection & Initialization
 * SQLite via PDO
 */

function get_db(): PDO {
    static $pdo = null;
    
    if ($pdo !== null) {
        return $pdo;
    }
    
    $config = require __DIR__ . '/config.php';
    $dbPath = $config['paths']['database'];
    $dbDir = dirname($dbPath);
    
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }
    
    $isNewDb = !file_exists($dbPath);
    
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // Enable SQLite foreign keys & WAL mode for high performance and reliability
    $pdo->exec('PRAGMA foreign_keys = ON;');
    try {
        $pdo->exec('PRAGMA journal_mode = WAL;');
    } catch (\Throwable $e) {
        // Safe fallback on Windows if another process currently holds a file lock
    }
    
    if ($isNewDb) {
        $schemaPath = __DIR__ . '/../database/schema.sql';
        if (file_exists($schemaPath)) {
            $schemaSql = file_get_contents($schemaPath);
            $pdo->exec($schemaSql);
        }
    }
    
    return $pdo;
}
