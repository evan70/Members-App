<?php
/**
 * Asset Publisher Helper
 * 
 * Provides functions to automatically publish module assets to public directory.
 */

// Get project root (tg_helpers -> engine -> project root)
$helper_file = __FILE__;
$tg_helpers_dir = dirname($helper_file);
$engine_dir = dirname($tg_helpers_dir);
$project_root = dirname($engine_dir) . DIRECTORY_SEPARATOR;

defined('BASEPATH') or define('BASEPATH', $project_root);

/**
 * Publish module assets to public directory
 * 
 * Copies CSS and JS files from modules/{module}/css and modules/{module}/js
 * to public/module_assets/{module}/css and public/module_assets/{module}/js
 * 
 * Also handles nested modules like modules/{parent}/{child}/css -> public/module_assets/{parent}_{child}/css
 * 
 * @param string|null $module_name Specific module to publish, or null for all
 * @return array List of files copied
 */
function publish_assets(?string $module_name = null): array {
    $modules_path = BASEPATH . 'modules' . DIRECTORY_SEPARATOR;
    $public_path = BASEPATH . 'public' . DIRECTORY_SEPARATOR . 'module_assets' . DIRECTORY_SEPARATOR;
    $copied = [];
    
    // Ensure public/module_assets directory exists
    if (!is_dir($public_path)) {
        mkdir($public_path, 0755, true);
    }
    
    $modules = $module_name ? [$module_name] : get_module_names($modules_path);
    
    foreach ($modules as $module) {
        publish_module_assets($modules_path, $public_path, $module, $copied);
    }
    
    return $copied;
}

/**
 * Publish assets for a single module (including nested submodules)
 */
function publish_module_assets(string $modules_path, string $public_path, string $module, array &$copied): void {
    $module_path = $modules_path . $module . DIRECTORY_SEPARATOR;
    
    // Handle top-level css/js in module
    publish_asset_folder($module_path, $public_path, $module, $copied);
    
    // Handle nested submodules (e.g., members/login)
    $nested_dirs = glob($module_path . '*', GLOB_ONLYDIR);
    foreach ($nested_dirs as $nested_dir) {
        $nested_name = basename($nested_dir);
        // Skip common non-asset directories
        if (in_array($nested_name, ['views', 'models', 'controllers', 'assets'])) {
            continue;
        }
        $nested_module = $module . '_' . $nested_name;
        publish_asset_folder($nested_dir . DIRECTORY_SEPARATOR, $public_path, $nested_module, $copied);
    }
}

/**
 * Publish assets from a source folder to target
 */
function publish_asset_folder(string $source_path, string $public_path, string $module, array &$copied): void {
    // Copy CSS files
    $css_source = $source_path . 'css' . DIRECTORY_SEPARATOR;
    $css_target = $public_path . $module . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR;
    if (is_dir($css_source)) {
        if (!is_dir($css_target)) {
            mkdir($css_target, 0755, true);
        }
        $files = glob($css_source . '*.css');
        foreach ($files as $file) {
            $filename = basename($file);
            $target_file = $css_target . $filename;
            if (copy($file, $target_file)) {
                $copied[] = "module_assets/{$module}/css/{$filename}";
            }
        }
    }
    
    // Copy JS files
    $js_source = $source_path . 'js' . DIRECTORY_SEPARATOR;
    $js_target = $public_path . $module . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR;
    if (is_dir($js_source)) {
        if (!is_dir($js_target)) {
            mkdir($js_target, 0755, true);
        }
        $files = glob($js_source . '*.js');
        foreach ($files as $file) {
            $filename = basename($file);
            $target_file = $js_target . $filename;
            if (copy($file, $target_file)) {
                $copied[] = "module_assets/{$module}/js/{$filename}";
            }
        }
    }
}

/**
 * Get list of module names from modules directory
 * 
 * @param string $modules_path Path to modules directory
 * @return array List of module names
 */
function get_module_names(string $modules_path): array {
    $modules = [];
    if (is_dir($modules_path)) {
        $dirs = glob($modules_path . '*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $modules[] = basename($dir);
        }
    }
    return $modules;
}

/**
 * Get asset URL for a module
 * 
 * @param string $filename Asset filename
 * @param string $module Module name
 * @param string $type Asset type (css or js)
 * @return string Asset URL
 */
function module_asset_url(string $filename, string $module, string $type = 'css'): string {
    $base_url = defined('BASE_URL') ? BASE_URL : '';
    return $base_url . 'module_assets/' . $module . '/' . $type . '/' . $filename;
}
