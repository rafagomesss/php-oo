<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

define('VIEWS_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('VIEWS_SITE_PATH', VIEWS_PATH . 'site' . DIRECTORY_SEPARATOR);
define('VIEWS_INCLUDES_PATH', VIEWS_PATH . 'includes' . DIRECTORY_SEPARATOR);

define('ASSETS_PATH', DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR);
define('CSS_PATH', ASSETS_PATH .  'css' . DIRECTORY_SEPARATOR);
define('JS_PATH', ASSETS_PATH .  'js' . DIRECTORY_SEPARATOR);

define('HOME', 'http://localhost:3000');
define('APP_DEBUG', true);

/**
 * Database config
 */

define('DB_NAME', 'oo_blog');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_CHARSET', 'UTF8');