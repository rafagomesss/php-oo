<?php
require __DIR__ . '/vendor/autoload.php';

define('HOME', 'http://localhost:3030');
define('DS', DIRECTORY_SEPARATOR);

define('VIEWS_PATH', __DIR__ . DS . 'views' . DS);
define('APP_DEBUG', true);
define('UPLOAD_PATH',  __DIR__ . DS . 'public' . DS . 'uploads' . DS);

/**
 * Database config
 */
define('DB_NAME', 'projeto_final_loja');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_CHARSET', 'UTF8');
