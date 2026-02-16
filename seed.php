#!/usr/bin/env php
<?php
/**
 * Seed Runner CLI
 * 
 * Run from project root: php seed.php [seed_name]
 * 
 * Options:
 *   php seed.php              - Run all seeds
 *   php seed.php [seed_name]  - Run specific seed
 *   php seed.php --list       - List available seeds
 */

$basepath = __DIR__ . '/';
$seeds_path = $basepath . 'database/seeds/';

// Load required classes
require_once $basepath . 'config/database.php';
require_once $basepath . 'engine/Trongate.php';
require_once $basepath . 'modules/db/Db.php';
require_once $seeds_path . 'Seed.php';

// Make the $databases array globally accessible
if (isset($databases)) {
    $GLOBALS['databases'] = $databases;
}

$arg = $argv[1] ?? null;

if ($arg === '--list') {
    echo "Available seeds:\n";
    $files = glob($seeds_path . '*.php');
    foreach ($files as $file) {
        $name = basename($file, '.php');
        if ($name !== 'Seed') {
            echo "  - $name\n";
        }
    }
    exit;
}

if ($arg) {
    // Run specific seed
    $seed_file = $seeds_path . $arg . '.php';
    if (!file_exists($seed_file)) {
        echo "Seed not found: $arg\n";
        exit(1);
    }
    
    require_once $seed_file;
    $class = $arg;
    if (!class_exists($class)) {
        echo "Class not found: $class\n";
        exit(1);
    }
    
    echo "Running seed: $arg\n";
    $seed = new $class();
    $seed->run();
    echo "Done!\n";
} else {
    // Run all seeds
    echo "Running all seeds...\n";
    $files = glob($seeds_path . '*.php');
    foreach ($files as $file) {
        $name = basename($file, '.php');
        if ($name === 'Seed') continue;
        
        require_once $file;
        $class = $name;
        
        if (!class_exists($class)) {
            echo "Warning: Class $class not found in $file\n";
            continue;
        }
        
        echo "Running seed: $name\n";
        $seed = new $class();
        $seed->run();
    }
    echo "All seeds completed!\n";
}
