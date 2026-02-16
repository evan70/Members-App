#!/usr/bin/env php
<?php
/**
 * Asset Publisher CLI
 * 
 * Run from project root: php publish_assets.php [module_name]
 * 
 * Options:
 *   php publish_assets.php          - Publish all module assets
 *   php publish_assets.php [module] - Publish specific module assets
 */

$basepath = __DIR__ . '/';
require_once $basepath . 'engine/tg_helpers/assets_helper.php';

// Get command line arguments
$module = $argv[1] ?? null;

echo "Publishing module assets...\n";

$copied = publish_assets($module);

if (empty($copied)) {
    echo "No assets found to publish.\n";
} else {
    echo "Published " . count($copied) . " file(s):\n";
    foreach ($copied as $file) {
        echo "  - $file\n";
    }
}

echo "Done!\n";
