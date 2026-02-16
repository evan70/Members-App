<?php
/**
 * Application Configuration
 */

defined('ENV') or define('ENV', 'dev');
defined('BASE_URL') or define('BASE_URL', 'http://localhost:8003/');
defined('APP_NAME') or define('APP_NAME', 'Members App');
defined('DEFAULT_MODULE') or define('DEFAULT_MODULE', 'members');
defined('DEFAULT_CONTROLLER') or define('DEFAULT_CONTROLLER', 'members');
defined('DEFAULT_METHOD') or define('DEFAULT_METHOD', 'index');
defined('MODULE_ASSETS_TRIGGER') or define('MODULE_ASSETS_TRIGGER', 'module_assets/');
defined('INTERCEPTORS') or define('INTERCEPTORS', []);
defined('ERROR_404') or define('ERROR_404', 'welcome/index');
