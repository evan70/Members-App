<?php
session_start();

// Determine the base path (project root)
define('BASEPATH', dirname(__DIR__) . '/');

// Config files
require_once BASEPATH . 'config/config.php';
require_once BASEPATH . 'config/constants.php';
require_once BASEPATH . 'config/custom_routing.php';
require_once BASEPATH . 'config/database.php';
require_once BASEPATH . 'config/site_owner.php';
require_once BASEPATH . 'config/encryption.php';

// Make the $databases array globally accessible
// This is required for multi-database functionality
if (isset($databases)) {
    $GLOBALS['databases'] = $databases;
}

// Enhanced autoloader - supports both engine classes and module classes
spl_autoload_register(function ($class_name) {
    // Priority 1: Check engine directory for core framework classes
    $file = __DIR__ . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    // Priority 2: Check modules directory for module-based classes
    // This enables "everything is a module" philosophy (e.g., Db, future SQLite, Postgres, etc.)
    $module_name = strtolower($class_name);
    $module_file = __DIR__ . '/../modules/' . $module_name . '/' . $class_name . '.php';
    if (file_exists($module_file)) {
        require_once $module_file;
        return true;
    }
    
    return false;
});

/**
 * Retrieves the URL segments after processing custom routes.
 *
 * @return array Returns an associative array with 'assumed_url' and 'segments'.
 */
function get_segments(): array {
    // Parse the BASE_URL to determine how many segments to remove
    $base_url_parsed = parse_url(BASE_URL);
    $base_path = isset($base_url_parsed['path']) ? $base_url_parsed['path'] : '/';
    $base_path = trim($base_path, '/');
    $base_segments = $base_path !== '' ? explode('/', $base_path) : [];
    $num_segments_to_ditch = count($base_segments);

    // Build the assumed URL from server variables
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
    $assumed_url = $scheme . '://' . $host . $request_uri;
    
    // Apply custom routing
    $assumed_url = attempt_custom_routing($assumed_url);
    $data['assumed_url'] = $assumed_url;
    
    // Remove the base URL path to get segments
    $url_parsed = parse_url($assumed_url);
    $url_path = isset($url_parsed['path']) ? $url_parsed['path'] : '/';
    $url_path = trim($url_path, '/');
    $segments = $url_path !== '' ? explode('/', $url_path) : [];
    
    // Remove base path segments
    $data['segments'] = array_slice($segments, $num_segments_to_ditch);
    return $data;
}

/**
 * Cached custom-route matching
 *
 * @param string $url The original target URL to potentially replace.
 * @return string Returns the updated URL if a custom route match is found, otherwise returns the original URL.
 */
function attempt_custom_routing(string $url): string {
    static $routes = [];
    static $db_routes = [];
    
    $path = ltrim(parse_url($url, PHP_URL_PATH) ?: '/', '/');
    $base_path = ltrim(parse_url(BASE_URL, PHP_URL_PATH) ?: '/', '/');
    if ($base_path !== '' && strpos($path, $base_path) === 0) {
        $path = substr($path, strlen($base_path));
    }
    
    // First check database routes
    if (empty($db_routes) && isset($GLOBALS['databases'])) {
        try {
            $db = new Db();
            if ($db->table_exists('trongate_routes')) {
                $sql = "SELECT route_pattern, destination FROM trongate_routes WHERE active = 1 ORDER BY priority DESC";
                $db_routes = $db->query($sql, 'array') ?: [];
            }
        } catch (Exception $e) {
            // Database not available yet, skip db routes
            $db_routes = [];
        }
    }
    
    // Try database routes first (higher priority)
    foreach ($db_routes as $route) {
        $pattern = $route['route_pattern'];
        $dest = $route['destination'];
        
        // Convert Trongate route syntax to regex
        $regex = '#^' . strtr($pattern, [
            '/' => '\/',
            '(:num)' => '(\d+)',
            '(:any)' => '([^\/]+)'
        ]) . '$#';
        
        if (preg_match($regex, $path, $matches)) {
            $match_count = count($matches);
            for ($i = 1; $i < $match_count; $i++) {
                $dest = str_replace('$' . $i, $matches[$i], $dest);
            }
            return rtrim(BASE_URL . $dest, '/');
        }
    }
    
    // Then check config routes
    if (empty($routes)) {
        if (!defined('CUSTOM_ROUTES') || empty(CUSTOM_ROUTES)) {
            return $url;
        }
        foreach (CUSTOM_ROUTES as $pattern => $dest) {
            $regex = '#^' . strtr($pattern, [
                '/' => '\/',
                '(:num)' => '(\d+)',
                '(:any)' => '([^\/]+)'
            ]) . '$#';
            $routes[] = [$regex, $dest];
        }
    }
    
    foreach ($routes as [$regex, $dest]) {
        if (preg_match($regex, $path, $matches)) {
            $match_count = count($matches);
            for ($i = 1; $i < $match_count; $i++) {
                $dest = str_replace('$' . $i, $matches[$i], $dest);
            }
            return rtrim(BASE_URL . $dest, '/');
        }
    }
    return $url;
}

// Define core constants
define('APPPATH', str_replace("\\", "/", dirname(dirname(__FILE__)) . '/'));
define('REQUEST_TYPE', $_SERVER['REQUEST_METHOD']);

// Process URL and routing
$data = get_segments();
define('SEGMENTS', $data['segments']);
define('ASSUMED_URL', $data['assumed_url']);

// Helper files - Load after constants are defined
require_once 'tg_helpers/flashdata_helper.php';
require_once 'tg_helpers/form_helper.php';
require_once 'tg_helpers/string_helper.php';
require_once 'tg_helpers/url_helper.php';
require_once 'tg_helpers/utilities_helper.php';

/* --------------------------------------------------------------
 * Interceptor execution (early hooks)
 * -------------------------------------------------------------- */
if (defined('INTERCEPTORS') && is_array(INTERCEPTORS)) {
    foreach (INTERCEPTORS as $module => $method) {
        $controller_path = APPPATH . "modules/{$module}/" . ucfirst($module) . '.php';

        if (!is_file($controller_path)) {
            throw new RuntimeException("Interceptor controller not found: {$controller_path}");
        }

        require_once $controller_path;

        $class = ucfirst($module);
        if (!class_exists($class, false)) {
            throw new RuntimeException("Interceptor class {$class} not defined");
        }

        $instance = new $class($module);

        if (!is_callable([$instance, $method])) {
            throw new RuntimeException("Interceptor method {$class}::{$method} is not callable");
        }

        $instance->{$method}();
    }
}