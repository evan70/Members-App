#!/usr/bin/env php
<?php
/**
 * Database Manager CLI
 * 
 * Manage SQLite database - export, import, and switch configurations.
 */

$basepath = __DIR__ . '/';

$arg = $argv[1] ?? null;

if (!$arg) {
    echo "Database Manager\n";
    echo "Usage: php db.php [command]\n\n";
    echo "Commands:\n";
    echo "  export [file]   - Export SQLite database to SQL file\n";
    echo "  import [file]   - Import SQL file to SQLite database\n";
    echo "  info            - Show current database info\n";
    exit;
}

require_once $basepath . 'config/database.php';

if (isset($databases)) {
    $GLOBALS['databases'] = $databases;
}

switch ($arg) {
    case 'info':
        show_db_info();
        break;
    case 'export':
        $file = $argv[2] ?? 'database.sql';
        export_db($file);
        break;
    case 'import':
        $file = $argv[2] ?? null;
        if (!$file) {
            echo "Error: Please specify a file to import\n";
            exit(1);
        }
        import_db($file);
        break;
    default:
        echo "Unknown command: $arg\n";
        exit(1);
}

function show_db_info() {
    $config = $GLOBALS['databases']['default'] ?? null;
    
    if (!$config) {
        echo "No database configured\n";
        return;
    }
    
    echo "Current Database Configuration:\n";
    echo "  Driver: " . ($config['driver'] ?? 'unknown') . "\n";
    
    if ($config['driver'] === 'sqlite') {
        echo "  Database: " . ($config['database'] ?? 'unknown') . "\n";
        if (file_exists($config['database'])) {
            $size = filesize($config['database']);
            echo "  Size: " . number_format($size) . " bytes\n";
        }
    } else {
        echo "  Host: " . ($config['host'] ?? 'localhost') . "\n";
        echo "  Port: " . ($config['port'] ?? '3306') . "\n";
        echo "  Database: " . ($config['database'] ?? 'unknown') . "\n";
        echo "  User: " . ($config['user'] ?? 'unknown') . "\n";
    }
}

function export_db(string $file) {
    $config = $GLOBALS['databases']['default'] ?? null;
    
    if ($config['driver'] !== 'sqlite') {
        echo "Error: Export only works with SQLite\n";
        exit(1);
    }
    
    $db_file = $config['database'];
    if (!file_exists($db_file)) {
        echo "Error: Database file not found: $db_file\n";
        exit(1);
    }
    
    // Use sqlite3 CLI to export
    $cmd = "sqlite3 " . escapeshellarg($db_file) . " .schema";
    $schema = shell_exec($cmd);
    
    $cmd = "sqlite3 " . escapeshellarg($db_file) . " .dump";
    $data = shell_exec($cmd);
    
    $output = "-- Exported from SQLite database\n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
    $output .= $data;
    
    file_put_contents($file, $output);
    echo "Exported database to: $file\n";
}

function import_db(string $file) {
    $config = $GLOBALS['databases']['default'] ?? null;
    
    if ($config['driver'] !== 'sqlite') {
        echo "Error: Import only works with SQLite\n";
        exit(1);
    }
    
    if (!file_exists($file)) {
        echo "Error: File not found: $file\n";
        exit(1);
    }
    
    $db_file = $config['database'];
    
    // Backup existing database
    if (file_exists($db_file)) {
        $backup = $db_file . '.backup';
        copy($db_file, $backup);
        echo "Backed up existing database to: $backup\n";
    }
    
    // Import SQL file
    $cmd = "sqlite3 " . escapeshellarg($db_file) . " < " . escapeshellarg($file);
    exec($cmd);
    
    echo "Imported SQL file to database: $db_file\n";
}
