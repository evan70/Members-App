<?php
/**
 * Database Configuration
 * 
 * Configure your database connections here.
 * Supports MySQL, MariaDB, and SQLite.
 */

$databases['default'] = [
    'driver' => 'sqlite',
    'database' => dirname(__DIR__) . '/writable/database.sqlite'
];
