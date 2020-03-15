<?php

namespace core;

use models\User;

class App
{
    public static $db;

    public $config;

    public function __construct($config)
    {
        $this->config = $config;
        self::$db = new DbConnection(Helpers::getValue($config, 'db'));
    }

    public function run()
    {
        require ROOT . '/models/User.php';
        $user = User::findOne(['id' => 3]);
        var_dump($user, $user->delete());
        exit();
    }
}