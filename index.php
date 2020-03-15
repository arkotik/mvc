<?php
define('ROOT', __DIR__);

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

$app = new \core\App($config);
$app->run();