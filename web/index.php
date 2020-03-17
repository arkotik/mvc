<?php
define('ROOT', __DIR__);
define('ENV_DEV', 'dev');
define('ENV_PROD', 'prod');

$core = scandir(ROOT . '/core');
foreach ($core as $item) {
    $entry = ROOT . '/core/' . $item;
    if (preg_match("/\.php$/", $item) && is_file($entry)) {
        /** @noinspection PhpIncludeInspection */
        require_once $entry;
    }
}

$config = array_merge(
    require ROOT . '/config/main.php',
    require ROOT . '/config/main-local.php'
);

define('APP_ENV', \core\Helpers::getValue($config, 'env', ENV_DEV));
define('APP_DEBUG', \core\Helpers::getValue($config, 'debug', false));
define('DIRECTORY_SEPARATOR', \core\Helpers::getValue($config, 'directory_separator', '/'));

if (APP_ENV === ENV_DEV && APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

(new \core\App($config))->run();