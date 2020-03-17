<?php

namespace core;

use controllers\SiteController;
use models\User;

/**
 * Class App
 * @package core
 *
 * @property array $config
 * @property BaseController $controller
 */
class App
{
    /** @var DbConnection */
    public static $db;
    
    /** @var App */
    public static $app;
    
    /** @var array */
    public $config;
    
    /** @var BaseController */
    public $controller;
    
    /** @var Request */
    public $request;

    public function __construct($config)
    {
        self::$app = $this;
        $this->config = $config;
        self::$db = new DbConnection(Helpers::getValue($config, 'db'));
    }

    public function run()
    {
        $this->request = new Request();
//        require ROOT . '/controllers/SiteController.php';
//        $this->controller = new SiteController();
//        echo $this->controller->actionIndex();

//        exit();
//        require ROOT . '/models/User.php';
//        $user = User::findOne(['id' => 3]);
//        var_dump($user, $user->delete());
    }
    
    public function getLayoutPath()
    {
        return ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Helpers::getValue($this->config, 'layoutPath', 'layout');
    }
}