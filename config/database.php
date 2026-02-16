<?php
/**
 * Database Configuration
 * 
 * Configure your database connections here.
 * Supports MySQL, MariaDB, and SQLite.
 * 
 * To switch between databases, change the 'driver' option.
 * 
 * SQLite Example:
 * $databases['default'] = [
 *     'driver' => 'sqlite',
 *     'database' => dirname(__DIR__) . '/writable/database.sqlite'
 * ];
 * 
 * MySQL Example:
 * $databases['default'] = [
 *     'driver' => 'mysql',
 *     'host' => 'localhost',
 *     'port' => '3306',
 *     'database' => 'myapp',
 *     'user' => 'root',
 *     'password' => ''
 * ];
 */

$databases['default'] = [
    'driver' => 'sqlite',
    'database' => dirname(__DIR__) . '/writable/database.sqlite'
];
